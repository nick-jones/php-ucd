<?php

namespace UCD\Unicode\Character\Repository\Capability;

use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Codepoint;
use UCD\Unicode\CodepointAssigned;

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