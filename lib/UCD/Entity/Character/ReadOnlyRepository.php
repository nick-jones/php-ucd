<?php

namespace UCD\Entity\Character;

use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;

interface ReadOnlyRepository extends \Countable
{
    /**
     * @param Codepoint $codepoint
     * @return CodepointAssigned
     * @throws CharacterNotFoundException
     */
    public function getByCodepoint(Codepoint $codepoint);

    /**
     * @return CodepointAssigned[]
     */
    public function getAll();
}