<?php

namespace UCD\Entity\Character\Properties\Decomposition;

use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\Properties\Decomposition;
use UCD\Entity\Character\Properties\DecompositionType;

class Assigned extends Decomposition
{
    /**
     * @var Codepoint[]
     */
    private $mappedTo;

    /**
     * @param DecompositionType $type
     * @param Codepoint[] $mappedTo
     */
    public function __construct(DecompositionType $type, array $mappedTo)
    {
        $this->mappedTo = $mappedTo;

        parent::__construct($type);
    }
}