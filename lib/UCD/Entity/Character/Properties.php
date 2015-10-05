<?php

namespace UCD\Entity\Character;


use UCD\Entity\Character\Properties\Bidirectionality;
use UCD\Entity\Character\Properties\General;
use UCD\Entity\Character\Properties\Normalization;
use UCD\Entity\Character\Properties\Numericity;
use UCD\Entity\Character\Properties\Shaping;

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
     * @var Shaping
     */
    private $shaping;

    /**
     * @param General $general
     * @param Numericity $numericity
     * @param Normalization $normalization
     * @param Bidirectionality $bidirectionality
     * @param Shaping $shaping
     */
    public function __construct(
        General $general,
        Numericity $numericity,
        Normalization $normalization,
        Bidirectionality $bidirectionality,
        Shaping $shaping
    ) {
        $this->general = $general;
        $this->numericity = $numericity;
        $this->normalization = $normalization;
        $this->bidirectionality = $bidirectionality;
        $this->shaping = $shaping;
    }

    /**
     * @return General
     */
    public function getGeneral()
    {
        return $this->general;
    }

    /**
     * @return Numericity
     */
    public function getNumericity()
    {
        return $this->numericity;
    }

    /**
     * @return bool
     */
    public function isNumeric()
    {
        return $this->numericity instanceof Numericity\Numeric;
    }

    /**
     * @return Normalization
     */
    public function getNormalization()
    {
        return $this->normalization;
    }

    /**
     * @return Bidirectionality
     */
    public function getBidirectionality()
    {
        return $this->bidirectionality;
    }

    /**
     * @return Shaping
     */
    public function getShaping()
    {
        return $this->shaping;
    }
}