<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;

use UCD\Entity\Character;
use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\Properties\Bidirectionality;
use UCD\Entity\Character\Properties\Bidirectionality\BracketBidirectionality;
use UCD\Entity\Character\Properties\Bidirectionality\Bracket;
use UCD\Entity\Character\Properties\Bidirectionality\Classing;
use UCD\Entity\Character\Properties\Bidirectionality\Mirroring;
use UCD\Entity\Character\Properties\General;
use UCD\Entity\Character\Properties\General\Block;
use UCD\Entity\Character\Properties\Normalization\Combining;
use UCD\Entity\Character\Properties\Normalization\Decomposition;
use UCD\Entity\Character\Properties\Normalization\DecompositionType;
use UCD\Entity\Character\Properties\General\GeneralCategory;
use UCD\Entity\Character\Properties\General\Name;
use UCD\Entity\Character\Properties\General\Names;
use UCD\Entity\Character\Properties\Normalization;
use UCD\Entity\Character\Properties\Numericity;
use UCD\Entity\Character\Properties\General\Version;
use UCD\Entity\Character\Properties;
use UCD\Entity\Character\Properties\Shaping;
use UCD\Entity\Character\Properties\Shaping\Joining;
use UCD\Entity\Character\Properties\Shaping\JoiningGroup;
use UCD\Entity\Character\Properties\Shaping\JoiningType;
use UCD\Exception\UnexpectedValueException;

class CharacterElementParser implements ElementParser
{
    const ATTR_AGE = 'age';
    const ATTR_NAME = 'na';
    const ATTR_NAME_VERSION_1 = 'na1';
    const ATTR_BLOCK = 'blk';
    const ATTR_CODEPOINT = 'cp';
    const ATTR_CODEPOINT_FIRST = 'first-cp';
    const ATTR_CODEPOINT_LAST = 'last-cp';
    const ATTR_GENERAL_CATEGORY = 'gc';
    const ATTR_CANONICAL_COMBINING_CLASS = 'ccc';
    const ATTR_BIDIRECTIONALITY_CLASS = 'bc';
    const ATTR_MIRRORED = 'Bidi_M';
    const ATTR_MIRROR_GLYPH = 'bmg';
    const ATTR_BIDIRECTIONALITY_CONTROL = 'Bidi_C';
    const ATTR_PAIRED_BRACKET_TYPE = 'bpt';
    const ATTR_PAIRED_BRACKET = 'bpb';
    const ATTR_DECOMPOSITION_TYPE = 'dt';
    const ATTR_DECOMPOSITION_MAPPING = 'dm';
    const ATTR_NUMERIC_TYPE = 'nt';
    const ATTR_NUMERIC_VALUE = 'nv';
    const ATTR_JOINING_GROUP = 'jg';
    const ATTR_JOINING_TYPE = 'jt';
    const ATTR_JOIN_CONTROL = 'Join_C';

    /**
     * @var \DOMElement
     */
    private $element;

    /**
     * @param \DOMElement $element
     * @return Character[]
     */
    public function parseElement(\DOMElement $element)
    {
        $this->element = $element;

        return $this->parse();
    }

    /**
     * @return \Generator|Character[]
     */
    private function parse()
    {
        $codepointValues = $this->extractCodepoints();

        foreach ($codepointValues as $codepointValue) {
            $codepoint = Codepoint::fromInt($codepointValue);
            $properties = $this->parseProperties($codepoint);
            yield new Character($codepoint, $properties);
        }
    }

    /**
     * @param Codepoint $codepoint
     * @return Properties
     */
    private function parseProperties(Codepoint $codepoint)
    {
        $general = $this->parseGeneral($codepoint);
        $normalization = $this->parseNormalization($codepoint);
        $numericity = $this->parseNumericity();
        $bidirectionality = $this->parseBidirectionality();
        $shaping = $this->parseShaping();

        return new Properties($general, $numericity, $normalization, $bidirectionality, $shaping);
    }

    /**
     * @param Codepoint $codepoint
     * @return General
     */
    private function parseGeneral(Codepoint $codepoint)
    {
        $names = $this->parseNames($codepoint);
        $block = $this->parseBlock();
        $age = $this->parseAge();;
        $generalCategory = $this->parseGeneralCategory();

        return new General($names, $block, $age, $generalCategory);
    }

    /**
     * @param Codepoint $codepoint
     * @return Names
     */
    private function parseNames(Codepoint $codepoint)
    {
        $primaryNameValue = $this->parsePlaceholders($this->getOptionalAttribute(self::ATTR_NAME), $codepoint);
        $primaryName = ($primaryNameValue === null) ? new Name\Unassigned() : new Name\Assigned($primaryNameValue);
        $version1NameValue = $this->parsePlaceholders($this->getOptionalAttribute(self::ATTR_NAME_VERSION_1), $codepoint);
        $version1Name = ($version1NameValue === null) ? new Name\Unassigned() : new Name\Assigned($version1NameValue);

        return new Names($primaryName, [], $version1Name);
    }

    /**
     * @return Block
     */
    private function parseBlock()
    {
        return new Block($this->getAttribute(self::ATTR_BLOCK));
    }

    /**
     * @return Version
     */
    private function parseAge()
    {
        return new Version($this->getAttribute(self::ATTR_AGE));
    }

    /**
     * @param Codepoint $codepoint
     * @return Normalization
     */
    private function parseNormalization(Codepoint $codepoint)
    {
        $combining = $this->parseCombiningClass();
        $decomposition = $this->parseDecomposition($codepoint);

        return new Normalization($combining, $decomposition);
    }

    /**
     * @return GeneralCategory
     */
    private function parseGeneralCategory()
    {
        return new GeneralCategory($this->getAttribute(self::ATTR_GENERAL_CATEGORY));
    }

    /**
     * @return Combining
     */
    private function parseCombiningClass()
    {
        return new Combining((int)$this->getAttribute(self::ATTR_CANONICAL_COMBINING_CLASS));
    }

    /**
     * @return Bidirectionality|BracketBidirectionality
     */
    private function parseBidirectionality()
    {
        $class = new Classing($this->getAttribute(self::ATTR_BIDIRECTIONALITY_CLASS));
        $isMirrored = $this->getBoolAttribute(self::ATTR_MIRRORED);
        $mirroredByValue = $this->getOptionalAttribute(self::ATTR_MIRROR_GLYPH);
        $mirroredBy = ($mirroredByValue !== null) ? Codepoint::fromHex($mirroredByValue) : null;
        $mirroring = new Mirroring($isMirrored, $mirroredBy);
        $isControl = $this->getBoolAttribute(self::ATTR_BIDIRECTIONALITY_CONTROL);
        $bracketType = $this->getAttribute(self::ATTR_PAIRED_BRACKET_TYPE);

        if ($bracketType === 'n') {
            return new Bidirectionality($class, $mirroring, $isControl);
        }

        $pairedWith = Codepoint::fromHex($this->getAttribute(self::ATTR_PAIRED_BRACKET));
        $bracket = ($bracketType === 'o')? Bracket::createOpen($pairedWith) : Bracket::createClose($pairedWith);

        return new BracketBidirectionality($class, $mirroring, $isControl, $bracket);
    }

    /**
     * @param Codepoint $codepoint
     * @return Decomposition\Assigned|Decomposition\Void
     */
    private function parseDecomposition(Codepoint $codepoint)
    {
        $decompositionType = new DecompositionType($this->getAttribute(self::ATTR_DECOMPOSITION_TYPE));
        $mapping = $this->getAttribute(self::ATTR_DECOMPOSITION_MAPPING);
        $placeholders = $this->parsePlaceholders($mapping, $codepoint);
        $decompositionMap = $this->parseCodepointList($placeholders);
        $count = count($decompositionMap);
        $isVoid = $count === 0 || ($count === 1 && $decompositionMap[0]->equals($codepoint));

        if ($isVoid) {
            return new Decomposition\Void($decompositionType);
        }

        return new Decomposition\Assigned($decompositionType, $decompositionMap);
    }

    /**
     * @return Numericity\NonNumeric|Numericity\Numeric
     * @throws UnexpectedValueException
     */
    private function parseNumericity()
    {
        $numericType = new Numericity\NumericType($this->getAttribute(self::ATTR_NUMERIC_TYPE));
        $numericValue = $this->getAttribute(self::ATTR_NUMERIC_VALUE);

        if ($numericValue === 'NaN') {
            return new Numericity\NonNumeric($numericType);
        }

        $numericValue = Numericity\RationalNumber::fromString($numericValue);

        return new Numericity\Numeric($numericType, $numericValue);
    }

    /**
     * @return Shaping
     */
    private function parseShaping()
    {
        $joining = $this->parseJoining();

        return new Shaping($joining);
    }

    /**
     * @return Joining
     */
    private function parseJoining()
    {
        $joiningGroup = new JoiningGroup($this->getAttribute(self::ATTR_JOINING_GROUP));
        $joiningType = new JoiningType($this->getAttribute(self::ATTR_JOINING_TYPE));
        $joinControl = $this->getBoolAttribute(self::ATTR_JOIN_CONTROL);

        return new Joining($joiningGroup, $joiningType, $joinControl);
    }

    /**
     * @return int[]
     */
    private function extractCodepoints()
    {
        if ($this->element->hasAttribute(self::ATTR_CODEPOINT)) {
            $first = hexdec($this->getAttribute(self::ATTR_CODEPOINT));
            $last = $first;
        } else {
            $first = hexdec($this->getAttribute(self::ATTR_CODEPOINT_FIRST));
            $last = hexdec($this->getAttribute(self::ATTR_CODEPOINT_LAST));
        }

        return range($first, $last);
    }

    /**
     * @param string $name
     * @return string|null
     */
    private function getOptionalAttribute($name)
    {
        $attribute = $this->element->getAttribute($name);

        if ($attribute === '') {
            return null;
        }

        return $attribute;
    }

    /**
     * @param string $name
     * @return string
     * @throws UnexpectedValueException
     */
    private function getAttribute($name)
    {
        $attribute = $this->element->getAttribute($name);

        if ($attribute === '') {
            throw new UnexpectedValueException(sprintf('Missing attribute "%s"', $name));
        }

        return $attribute;
    }

    /**
     * @param string $name
     * @return bool
     */
    private function getBoolAttribute($name)
    {
        return $this->getAttribute($name) === 'Y';
    }

    /**
     * @param string $value
     * @param Codepoint $codepoint
     * @return string
     */
    private function parsePlaceholders($value, Codepoint $codepoint)
    {
        if ($value === null) {
            return null;
        }

        $hexCodepoint = sprintf('%X', $codepoint->getValue());

        return str_replace('#', $hexCodepoint, $value);
    }

    /**
     * @param string $list
     * @return Codepoint[]
     */
    private function parseCodepointList($list)
    {
        $mapper = function ($codepointValue) {
            return Codepoint::fromHex($codepointValue);
        };

        return array_map($mapper, explode(' ', $list));
    }
}