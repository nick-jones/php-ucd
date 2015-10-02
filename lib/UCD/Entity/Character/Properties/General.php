<?php

namespace UCD\Entity\Character\Properties;

use UCD\Entity\Character\Properties\General\Block;
use UCD\Entity\Character\Properties\General\GeneralCategory;
use UCD\Entity\Character\Properties\General\Names;
use UCD\Entity\Character\Properties\General\Version;

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
     * @param Names $names
     * @param Block $block
     * @param Version $age
     * @param GeneralCategory $generalCategory
     */
    public function __construct(Names $names, Block $block, Version $age, GeneralCategory $generalCategory)
    {
        $this->names = $names;
        $this->block = $block;
        $this->age = $age;
        $this->generalCategory = $generalCategory;
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
}