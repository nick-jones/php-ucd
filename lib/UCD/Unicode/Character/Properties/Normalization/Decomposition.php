<?php

namespace UCD\Unicode\Character\Properties\Normalization;

abstract class Decomposition
{
    /**
     * @var DecompositionType
     */
    private $type;

    /**
     * @param DecompositionType $type
     */
    public function __construct(DecompositionType $type)
    {
        $this->type = $type;
    }

    /**
     * @return DecompositionType
     */
    public function getType()
    {
        return $this->type;
    }
}