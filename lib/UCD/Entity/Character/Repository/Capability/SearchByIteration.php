<?php

namespace UCD\Entity\Character\Repository\Capability;

use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;

trait SearchByIteration
{
    /**
     * @param Codepoint $codepoint
     * @throws CharacterNotFoundException
     * @return CodepointAssigned
     */
    public function getByCodepoint(Codepoint $codepoint)
    {
        foreach ($this->getAll() as $character) {
            if ($codepoint->equals($character->getCodepoint())) {
                return $character;
            }
        }

        throw CharacterNotFoundException::withCodepoint($codepoint);
    }

    /**
     * @return CodepointAssigned[]
     */
    abstract public function getAll();
}