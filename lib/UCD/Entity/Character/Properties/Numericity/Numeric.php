<?php

namespace UCD\Entity\Character\Properties\Numericity;

use UCD\Entity\Character\Properties\Numericity;

class Numeric extends Numericity
{
    /**
     * @var RationalNumber
     */
    private $number;

    /**
     * @param NumericType $type
     * @param RationalNumber $number
     */
    public function __construct(NumericType $type, RationalNumber $number)
    {
        $this->number = $number;

        parent::__construct($type);
    }

    /**
     * @return RationalNumber
     */
    public function getNumber()
    {
        return $this->number;
    }
}