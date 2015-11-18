<?php

namespace UCD\Unicode\Character;

use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Codepoint;
use UCD\Unicode\CodepointAssigned;

interface Repository extends \Countable
{
    /**
     * @param Codepoint $codepoint
     * @return CodepointAssigned
     * @throws CharacterNotFoundException
     */
    public function getByCodepoint(Codepoint $codepoint);

    /**
     * @return Collection
     */
    public function getAll();
}