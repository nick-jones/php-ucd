<?php

namespace UCD\Entity\Character\Properties;

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
}