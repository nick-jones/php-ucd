<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;
use UCD\Entity\Character\Properties\General;
use UCD\Entity\Character\Properties\General\Block;
use UCD\Entity\Character\Properties\General\GeneralCategory;
use UCD\Entity\Character\Properties\General\Name;
use UCD\Entity\Character\Properties\General\Names;
use UCD\Entity\Character\Properties\General\Version;

use UCD\Exception\UnexpectedValueException;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

abstract class Base implements ElementParser
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
    protected $element;

    /**
     * @return CodepointAssigned[]
     */
    abstract protected function parse();

    /**
     * @param \DOMElement $element
     * @return CodepointAssigned[]
     */
    public function parseElement(\DOMElement $element)
    {
        $this->element = $element;

        return $this->parse();
    }

    /**
     * @param Codepoint $codepoint
     * @return General
     */
    protected function parseGeneral(Codepoint $codepoint)
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
    protected function parseNames(Codepoint $codepoint)
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
    protected function parseBlock()
    {
        return new Block($this->getAttribute(self::ATTR_BLOCK));
    }

    /**
     * @return Version
     */
    protected function parseAge()
    {
        return new Version($this->getAttribute(self::ATTR_AGE));
    }

    /**
     * @return GeneralCategory
     */
    protected function parseGeneralCategory()
    {
        return new GeneralCategory($this->getAttribute(self::ATTR_GENERAL_CATEGORY));
    }

    /**
     * @return int[]
     */
    protected function extractCodepoints()
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
    protected function getOptionalAttribute($name)
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
    protected function getAttribute($name)
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
    protected function getBoolAttribute($name)
    {
        return $this->getAttribute($name) === 'Y';
    }

    /**
     * @param string $value
     * @param Codepoint $codepoint
     * @return string
     */
    protected function parsePlaceholders($value, Codepoint $codepoint)
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
    protected function parseCodepointList($list)
    {
        $mapper = function ($codepointValue) {
            return Codepoint::fromHex($codepointValue);
        };

        return array_map($mapper, explode(' ', $list));
    }
}