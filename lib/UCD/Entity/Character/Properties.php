<?php

namespace UCD\Entity\Character;

use UCD\Entity\Character\Properties\Bidirectionality;
use UCD\Entity\Character\Properties\Block;
use UCD\Entity\Character\Properties\Combining;
use UCD\Entity\Character\Properties\Decomposition;
use UCD\Entity\Character\Properties\GeneralCategory;
use UCD\Entity\Character\Properties\Names;
use UCD\Entity\Character\Properties\Numericity;
use UCD\Entity\Character\Properties\Version;

class Properties
{
    /**
     * @var Version
     */
    private $age;

    /**
     * @var Names
     */
    private $names;

    /**
     * @var Block
     */
    private $block;

    /**
     * @var GeneralCategory
     */
    private $generalCategory;

    /**
     * @var Combining
     */
    private $combining;

    /**
     * @var Bidirectionality
     */
    private $bidirectionality;

    /**
     * @var Decomposition
     */
    private $decomposition;

    /**
     * @var Numericity
     */
    private $numericity;

    /**
     * @param Version $age
     * @param Names $names
     * @param Block $block
     * @param GeneralCategory $generalCategory
     * @param Combining $combining
     * @param Bidirectionality $bidirectionality
     * @param Decomposition $decomposition
     * @param Numericity $numericity
     */
    public function __construct(
        Version $age,
        Names $names,
        Block $block,
        GeneralCategory $generalCategory,
        Combining $combining,
        Bidirectionality $bidirectionality,
        Decomposition $decomposition,
        Numericity $numericity
    ) {
        $this->age = $age;
        $this->names = $names;
        $this->block = $block;
        $this->generalCategory = $generalCategory;
        $this->combining = $combining;
        $this->bidirectionality = $bidirectionality;
        $this->decomposition = $decomposition;
        $this->numericity = $numericity;
    }
}