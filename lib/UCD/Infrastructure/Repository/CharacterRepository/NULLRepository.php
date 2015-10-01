<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Codepoint;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Character\WritableRepository;
use UCD\Entity\CodepointAssigned;

class NULLRepository implements WritableRepository
{
    /**
     * @param Codepoint $codepoint
     * @throws CharacterNotFoundException
     * @return CodepointAssigned
     */
    public function getByCodepoint(Codepoint $codepoint)
    {
        throw CharacterNotFoundException::withCodepoint($codepoint);
    }

    /**
     * @param CodepointAssigned[] $characters
     */
    public function addMany($characters)
    {

    }

    /**
     * @return CodepointAssigned[]
     */
    public function getAll()
    {
        return [];
    }

    /**
     * @return int
     */
    public function count()
    {
        return 0;
    }
}