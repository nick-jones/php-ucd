<?php

namespace UCD\Unicode;

use UCD\Unicode\Character\Properties\General;

class NonCharacter implements CodepointAssigned, Comparable
{
    /**
     * @var Codepoint
     */
    private $codepoint;

    /**
     * @var General
     */
    private $generalProperties;

    /**
     * @param Codepoint $codepoint
     * @param General $generalProperties
     */
    public function __construct(Codepoint $codepoint, General $generalProperties)
    {
        $this->codepoint = $codepoint;
        $this->generalProperties = $generalProperties;
    }

    /**
     * @return Codepoint
     */
    public function getCodepoint()
    {
        return $this->codepoint;
    }

    /**
     * @return General
     */
    public function getGeneralProperties()
    {
        return $this->generalProperties;
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