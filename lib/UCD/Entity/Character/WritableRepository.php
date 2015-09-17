<?php

namespace UCD\Entity\Character;

use UCD\Entity\Character;
use UCD\Entity\Character\Repository\AddCharacterException;

interface WritableRepository extends ReadOnlyRepository
{
    /**
     * @param Character[] $characters
     * @throws AddCharacterException
     */
    public function addMany($characters);
}