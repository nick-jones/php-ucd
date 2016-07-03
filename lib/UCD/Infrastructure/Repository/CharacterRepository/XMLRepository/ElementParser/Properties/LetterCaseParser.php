<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties;

use UCD\Unicode\Character\Properties\LetterCase\Mapping;
use UCD\Unicode\Character\Properties\LetterCase;
use UCD\Unicode\Character\Properties\LetterCase\Mappings;
use UCD\Unicode\Codepoint;

class LetterCaseParser extends BaseParser
{
    const ATTR_LOWERCASE_MAPPING = 'lc';
    const ATTR_SIMPLE_LOWERCASE_MAPPING = 'slc';
    const ATTR_UPPERCASE_MAPPING = 'uc';
    const ATTR_SIMPLE_UPPERCASE_MAPPING = 'suc';
    const ATTR_TITLECASE_MAPPING = 'tc';
    const ATTR_SIMPLE_TITLECASE_MAPPING = 'stc';
    const ATTR_FOLDING_MAPPING = 'cf';
    const ATTR_SIMPLE_FOLDING_MAPPING = 'scf';

    /**
     * @return LetterCase
     */
    protected function parse()
    {
        $mappings = $this->parseMappings();

        return new LetterCase($mappings);
    }

    /**
     * @return Mappings
     */
    private function parseMappings()
    {
        return new Mappings(
            $this->parseLowercaseMapping(),
            $this->parseUppercaseMapping(),
            $this->parseTitlecaseMapping(),
            $this->parseFoldingMapping()
        );
    }

    /**
     * @return Mapping
     */
    private function parseLowercaseMapping()
    {
        return new Mapping(
            $this->parseSimpleMapping(self::ATTR_SIMPLE_LOWERCASE_MAPPING),
            $this->parseMapping(self::ATTR_LOWERCASE_MAPPING)
        );
    }

    /**
     * @return Mapping
     */
    private function parseUppercaseMapping()
    {
        return new Mapping(
            $this->parseSimpleMapping(self::ATTR_SIMPLE_UPPERCASE_MAPPING),
            $this->parseMapping(self::ATTR_UPPERCASE_MAPPING)
        );
    }

    /**
     * @return Mapping
     */
    private function parseTitlecaseMapping()
    {
        return new Mapping(
            $this->parseSimpleMapping(self::ATTR_SIMPLE_TITLECASE_MAPPING),
            $this->parseMapping(self::ATTR_TITLECASE_MAPPING)
        );
    }

    /**
     * @return Mapping
     */
    private function parseFoldingMapping()
    {
        return new Mapping(
            $this->parseSimpleMapping(self::ATTR_SIMPLE_FOLDING_MAPPING),
            $this->parseMapping(self::ATTR_FOLDING_MAPPING)
        );
    }

    /**
     * @param string $attribute
     * @return Codepoint\Collection
     */
    private function parseMapping($attribute)
    {
        $list = $this->parsePlaceholders(
            $this->getAttribute($attribute),
            $this->codepoint
        );

        return Codepoint\Collection::fromArray(
            $this->parseCodepointList($list)
        );
    }


    /**
     * @param string $attribute
     * @return Codepoint
     */
    private function parseSimpleMapping($attribute)
    {
        $codepoint = $this->parsePlaceholders(
            $this->getAttribute($attribute),
            $this->codepoint
        );

        return Codepoint::fromHex($codepoint);
    }
}