<?php

namespace UCD\Unicode;

use UCD\Unicode\Codepoint;
use UCD\Unicode\Character\Properties;

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
     * @return Properties\General
     */
    public function getGeneralProperties()
    {
        return $this->properties->getGeneral();
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