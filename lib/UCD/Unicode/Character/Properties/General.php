<?php

namespace UCD\Unicode\Character\Properties;

use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Character\Properties\General\Names;
use UCD\Unicode\Character\Properties\General\Script;
use UCD\Unicode\Character\Properties\General\Version;

class General
{
    /**
     * @var Names
     */
    private $names;

    /**
     * @var Block
     */
    private $block;

    /**
     * @var Version
     */
    private $age;

    /**
     * @var GeneralCategory
     */
    private $generalCategory;

    /**
     * @var Script
     */
    private $script;

    /**
     * @param Names $names
     * @param Block $block
     * @param Version $age
     * @param GeneralCategory $generalCategory
     * @param Script $script
     */
    public function __construct(
        Names $names,
        Block $block,
        Version $age,
        GeneralCategory $generalCategory,
        Script $script
    ) {
        $this->names = $names;
        $this->block = $block;
        $this->age = $age;
        $this->generalCategory = $generalCategory;
        $this->script = $script;
    }

    /**
     * @return Names
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * @return Block
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @return Version
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @return GeneralCategory
     */
    public function getGeneralCategory()
    {
        return $this->generalCategory;
    }

    /**
     * @return Script
     */
    public function getScript()
    {
        return $this->script;
    }
}