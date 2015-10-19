<?php

namespace UCD\Entity\Character;

use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;

interface Repository extends \Countable
{
    /**
     * @param Codepoint $codepoint
     * @return CodepointAssigned
     * @throws CharacterNotFoundException
     */
    public function getByCodepoint(Codepoint $codepoint);

    /**
     * @return \Traversable|CodepointAssigned[]
     */
    public function getAll();
}