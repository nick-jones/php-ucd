<?php

namespace UCD\Entity\Character;

use UCD\Entity\Character;
use UCD\Entity\Character\Repository\CharacterNotFoundException;

interface ReadOnlyRepository extends \Countable
{
    /**
     * @param Codepoint $codepoint
     * @return Character
     * @throws CharacterNotFoundException
     */
    public function getByCodepoint(Codepoint $codepoint);

    /**
     * @return Character[]
     */
    public function getAll();
}