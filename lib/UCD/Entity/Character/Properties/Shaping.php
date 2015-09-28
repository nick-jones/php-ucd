<?php

namespace UCD\Entity\Character\Properties;

use UCD\Entity\Character\Properties\Shaping\Joining;

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
}