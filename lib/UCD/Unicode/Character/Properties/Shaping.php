<?php

namespace UCD\Unicode\Character\Properties;

use UCD\Unicode\Character\Properties\Shaping\Joining;

class Shaping
{
    /**
     * @var Joining
     */
    private $joining;

    /**
     * @param Joining $joining
     */
    public function __construct(Joining $joining)
    {
        $this->joining = $joining;
    }

    /**
     * @return Joining
     */
    public function getJoining()
    {
        return $this->joining;
    }
}