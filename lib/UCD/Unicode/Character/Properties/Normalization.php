<?php

namespace UCD\Unicode\Character\Properties;

use UCD\Unicode\Character\Properties\Normalization\Combining;
use UCD\Unicode\Character\Properties\Normalization\Decomposition;

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

    /**
     * @return Combining
     */
    public function getCombining()
    {
        return $this->combining;
    }

    /**
     * @return Decomposition
     */
    public function getDecomposition()
    {
        return $this->decomposition;
    }
}