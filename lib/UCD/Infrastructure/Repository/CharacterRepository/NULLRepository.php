<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Character\WritableRepository;

class NULLRepository implements WritableRepository
{
    /**
     * @param Codepoint $codepoint
     * @throws CharacterNotFoundException
     * @return Character
     */
    public function getByCodepoint(Codepoint $codepoint)
    {
        throw CharacterNotFoundException::withCodepoint($codepoint);
    }

    /**
     * @param Character[] $characters
     */
    public function addMany($characters)
    {

    }

    /**
     * @return Character[]
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