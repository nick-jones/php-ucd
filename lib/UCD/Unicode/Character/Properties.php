<?php

namespace UCD\Unicode\Character;


use UCD\Unicode\Character\Properties\Bidirectionality;
use UCD\Unicode\Character\Properties\LetterCase;
use UCD\Unicode\Character\Properties\General;
use UCD\Unicode\Character\Properties\Normalization;
use UCD\Unicode\Character\Properties\Numericity;
use UCD\Unicode\Character\Properties\Shaping;

class Properties
{
    /**
     * @var General
     */
    private $general;

    /**
     * @var LetterCase
     */
    private $letterCase;

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
     * @param LetterCase $letterCase
     * @param Numericity $numericity
     * @param Normalization $normalization
     * @param Bidirectionality $bidirectionality
     * @param Shaping $shaping
     */
    public function __construct(
        General $general,
        LetterCase $letterCase,
        Numericity $numericity,
        Normalization $normalization,
        Bidirectionality $bidirectionality,
        Shaping $shaping
    ) {
        $this->general = $general;
        $this->letterCase = $letterCase;
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
     * @return LetterCase
     */
    public function getLetterCase()
    {
        return $this->letterCase;
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