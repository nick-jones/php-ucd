<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use UCD\Entity\Character;
use UCD\Entity\Character\Properties;
use UCD\Entity\Character\Properties\Normalization\Combining;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Properties\Bidirectionality;
use UCD\Entity\Character\Properties\Bidirectionality\BracketBidirectionality;
use UCD\Entity\Character\Properties\Bidirectionality\Bracket;
use UCD\Entity\Character\Properties\Bidirectionality\Classing;
use UCD\Entity\Character\Properties\Bidirectionality\Mirroring;
use UCD\Entity\Character\Properties\General;
use UCD\Entity\Character\Properties\Normalization\Decomposition;
use UCD\Entity\Character\Properties\Normalization\DecompositionType;
use UCD\Entity\Character\Properties\General\Name;
use UCD\Entity\Character\Properties\Normalization;
use UCD\Entity\Character\Properties\Numericity;
use UCD\Entity\Character\Properties\Shaping;
use UCD\Entity\Character\Properties\Shaping\Joining;
use UCD\Entity\Character\Properties\Shaping\JoiningGroup;
use UCD\Entity\Character\Properties\Shaping\JoiningType;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

class CharacterParser extends Base
{
    /**
     * @return \Generator|Character[]
     */
    protected function parse()
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
    protected function parseProperties(Codepoint $codepoint)
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
     * @return Normalization
     */
    protected function parseNormalization(Codepoint $codepoint)
    {
        $combining = $this->parseCombiningClass();
        $decomposition = $this->parseDecomposition($codepoint);

        return new Normalization($combining, $decomposition);
    }

    /**
     * @param Codepoint $codepoint
     * @return Decomposition\Assigned|Decomposition\Void
     */
    protected function parseDecomposition(Codepoint $codepoint)
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
     * @return Combining
     */
    protected function parseCombiningClass()
    {
        return new Combining((int)$this->getAttribute(self::ATTR_CANONICAL_COMBINING_CLASS));
    }

    /**
     * @return Bidirectionality|BracketBidirectionality
     */
    protected function parseBidirectionality()
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
     * @return Numericity\NonNumeric|Numericity\Numeric
     */
    protected function parseNumericity()
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
    protected function parseShaping()
    {
        $joining = $this->parseJoining();

        return new Shaping($joining);
    }

    /**
     * @return Joining
     */
    protected function parseJoining()
    {
        $joiningGroup = new JoiningGroup($this->getAttribute(self::ATTR_JOINING_GROUP));
        $joiningType = new JoiningType($this->getAttribute(self::ATTR_JOINING_TYPE));
        $joinControl = $this->getBoolAttribute(self::ATTR_JOIN_CONTROL);

        return new Joining($joiningGroup, $joiningType, $joinControl);
    }
}