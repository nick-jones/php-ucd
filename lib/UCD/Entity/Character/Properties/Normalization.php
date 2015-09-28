<?php

namespace UCD\Entity\Character\Properties;

use UCD\Entity\Character\Properties\Normalization\Combining;
use UCD\Entity\Character\Properties\Normalization\Decomposition;

class Normalization
{
    /**
     * @var Combining
     */
    private $combining;

    /**
     * @var Decomposition
     */
    private $decomposition;

    /**
     * @param Combining $combining
     * @param Decomposition $decomposition
     */
    public function __construct(Combining $combining, Decomposition $decomposition)
    {
        $this->combining = $combining;
        $this->decomposition = $decomposition;
    }
}