<?php

namespace UCD\Entity\Character;

use UCD\Entity\Character\Repository\AddCharacterException;
use UCD\Entity\CodepointAssigned;

interface WritableRepository extends Repository, \SplSubject
{
    /**
     * @param CodepointAssigned[] $characters
     * @throws AddCharacterException
     */
    public function addMany($characters);
}