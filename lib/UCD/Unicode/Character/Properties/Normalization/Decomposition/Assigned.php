<?php

namespace UCD\Unicode\Character\Properties\Normalization\Decomposition;

use UCD\Unicode\Codepoint;
use UCD\Unicode\Character\Properties\Normalization\Decomposition;
use UCD\Unicode\Character\Properties\Normalization\DecompositionType;

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

    /**
     * @return Codepoint\Collection|Codepoint[]
     */
    public function getMappedTo()
    {
        return Codepoint\Collection::fromArray(
            $this->mappedTo
        );
    }
}