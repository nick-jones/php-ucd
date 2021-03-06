<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties;

use UCD\Unicode\Character\Properties\General;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Character\Properties\General\Name\Assigned;
use UCD\Unicode\Character\Properties\General\Name\Unassigned;
use UCD\Unicode\Character\Properties\General\Names;
use UCD\Unicode\Character\Properties\General\Name;
use UCD\Unicode\Character\Properties\General\Script;
use UCD\Unicode\Character\Properties\General\Version;

class GeneralParser extends BaseParser
{
    const ATTR_AGE = 'age';
    const ATTR_NAME = 'na';
    const ATTR_NAME_VERSION_1 = 'na1';
    const ATTR_BLOCK = 'blk';
    const ATTR_GENERAL_CATEGORY = 'gc';
    const ATTR_SCRIPT = 'sc';

    /**
     * @return General
     */
    protected function parse()
    {
        $names = $this->parseNames();
        $block = $this->parseBlock();
        $age = $this->parseAge();
        $generalCategory = $this->parseGeneralCategory();
        $script = $this->parseScript();

        return new General($names, $block, $age, $generalCategory, $script);
    }

    /**
     * @return Names
     */
    private function parseNames()
    {
        $primaryName = $this->parsePrimaryName();
        $version1Name = $this->parseVersion1Name();

        return new Names($primaryName, [], $version1Name);
    }

    /**
     * @return Name
     */
    private function parsePrimaryName()
    {
        return $this->parseNameWithValue(
            $this->getOptionalAttribute(self::ATTR_NAME)
        );
    }

    /**
     * @return Name
     */
    private function parseVersion1Name()
    {
        return $this->parseNameWithValue(
            $this->getOptionalAttribute(self::ATTR_NAME_VERSION_1)
        );
    }

    /**
     * @param $value
     * @return Assigned|Unassigned
     */
    private function parseNameWithValue($value)
    {
        $version1NameValue = $this->parsePlaceholders($value, $this->codepoint);

        if ($version1NameValue === null) {
            return new Unassigned();
        }

        return new Assigned($version1NameValue);
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
     * @return GeneralCategory
     */
    private function parseGeneralCategory()
    {
        return new GeneralCategory($this->getAttribute(self::ATTR_GENERAL_CATEGORY));
    }

    /**
     * @return Script
     */
    private function parseScript()
    {
        return new Script($this->getAttribute(self::ATTR_SCRIPT));
    }
}