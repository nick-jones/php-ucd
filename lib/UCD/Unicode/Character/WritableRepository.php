<?php

namespace UCD\Unicode\Character;

use UCD\Unicode\Character\Repository\AddCharacterException;
use UCD\Unicode\CodepointAssigned;

interface WritableRepository extends Repository, \SplSubject
{
    /**
     * @param Collection $characters
     * @throws AddCharacterException
     */
    public function addMany(Collection $characters);
}