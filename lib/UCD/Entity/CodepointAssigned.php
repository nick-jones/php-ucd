<?php

namespace UCD\Entity;

use UCD\Entity\Character\Codepoint;

interface CodepointAssigned
{
    /**
     * @return Codepoint
     */
    public function getCodepoint();
}