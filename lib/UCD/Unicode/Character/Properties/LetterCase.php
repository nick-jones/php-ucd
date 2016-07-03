<?php

namespace UCD\Unicode\Character\Properties;

use UCD\Unicode\Character\Properties\LetterCase\Mappings;

class LetterCase
{
    /**
     * @var Mappings
     */
    private $mappings;

    /**
     * @param Mappings $mappings
     */
    public function __construct(Mappings $mappings)
    {
        $this->mappings = $mappings;
    }

    /**
     * @return Mappings
     */
    public function getMappings()
    {
        return $this->mappings;
    }
}