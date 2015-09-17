<?php

namespace UCD\Entity;

use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\Properties;

class Character implements CodepointAssigned, Comparable
{
    /**
     * @var Codepoint
     */
    private $codepoint;

    /**
     * @var Properties
     */
    private $properties;

    /**
     * @param Codepoint $codepoint
     * @param Properties $properties
     */
    public function __construct(Codepoint $codepoint, Properties $properties)
    {
        $this->codepoint = $codepoint;
        $this->properties = $properties;
    }

    /**
     * @return Codepoint
     */
    public function getCodepoint()
    {
        return $this->codepoint;
    }

    /**
     * @return int
     */
    public function getCodepointValue()
    {
        return $this->codepoint->getValue();
    }

    /**
     * @return Properties
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param mixed $other
     * @return bool
     */
    public function equals($other)
    {
        if ($this === $other) {
            return true;
        }

        return $other instanceof self
            && $this->codepoint->equals($other->codepoint);
    }
}