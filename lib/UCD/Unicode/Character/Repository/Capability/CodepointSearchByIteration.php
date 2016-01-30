<?php

namespace UCD\Unicode\Character\Repository\Capability;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Codepoint;
use UCD\Unicode\CodepointAssigned;

trait CodepointSearchByIteration
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
     * @param Codepoint\Collection $codepoints
     * @return Character\Collection
     */
    public function getByCodepoints(Codepoint\Collection $codepoints)
    {
        return new Character\Collection(
            $this->unionAgainstCodepoints($codepoints)
        );
    }

    /**
     * @param Codepoint\Collection $codepoints
     * @return \Generator
     */
    protected function unionAgainstCodepoints(Codepoint\Collection $codepoints)
    {
        foreach ($this->getAll() as $character) {
            if ($codepoints->has($character->getCodepoint())) {
                yield $character;
            }
        }
    }

    /**
     * @return CodepointAssigned[]
     */
    abstract public function getAll();
}