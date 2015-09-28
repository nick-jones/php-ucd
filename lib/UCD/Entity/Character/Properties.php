<?php

namespace UCD\Entity\Character;


use UCD\Entity\Character\Properties\Bidirectionality;
use UCD\Entity\Character\Properties\General;
use UCD\Entity\Character\Properties\Normalization;
use UCD\Entity\Character\Properties\Numericity;

class Properties
{
    /**
     * @var General
     */
    private $general;

    /**
     * @var Numericity
     */
    private $numericity;

    /**
     * @var Normalization
     */
    private $normalization;

    /**
     * @var Bidirectionality
     */
    private $bidirectionality;

    /**
     * @param General $general
     * @param Numericity $numericity
     * @param Normalization $normalization
     * @param Bidirectionality $bidirectionality
     */
    public function __construct(
        General $general,
        Numericity $numericity,
        Normalization $normalization,
        Bidirectionality $bidirectionality
    ) {
        $this->general = $general;
        $this->numericity = $numericity;
        $this->normalization = $normalization;
        $this->bidirectionality = $bidirectionality;
    }
}