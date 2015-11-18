<?php

namespace UCD\Unicode\Character\Properties;

use UCD\Unicode\Character\Properties\Numericity\NumericType;

abstract class Numericity
{
    /**
     * @var NumericType
     */
    private $type;

    /**
     * @param NumericType $type
     */
    public function __construct(NumericType $type)
    {
        $this->type = $type;
    }

    /**
     * @return NumericType
     */
    public function getType()
    {
        return $this->type;
    }
}