<?php

namespace UCD\Unicode\Character;

use UCD\Unicode\Character;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Codepoint\Range;
use UCD\Unicode\CodepointAssigned;
use UCD\Unicode\Collection\TraversableBackedCollection;
use UCD\Unicode\NonCharacter;
use UCD\Unicode\Surrogate;

class Collection extends TraversableBackedCollection
{
    /**
     * @return Codepoint\Collection|Codepoint[]
     */
    public function extractCodepoints()
    {
        return new Codepoint\Collection(
            $this->yieldCodepoints()
        );
    }

    /**
     * @return \Generator
     */
    private function yieldCodepoints()
    {
        /** @var CodepointAssigned $character */
        foreach ($this as $character) {
            yield $character->getCodepoint();
        }
    }

    /**
     * @return static|Character[]
     */
    public function onlyCharacters()
    {
        return $this->filterWith(function (CodepointAssigned $assigned) {
            return $assigned instanceof Character;
        });
    }

    /**
     * @return static|NonCharacter[]
     */
    public function onlyNonCharacters()
    {
        return $this->filterWith(function (CodepointAssigned $assigned) {
            return $assigned instanceof NonCharacter;
        });
    }

    /**
     * @return static|Surrogate[]
     */
    public function onlySurrogates()
    {
        return $this->filterWith(function (CodepointAssigned $assigned) {
            return $assigned instanceof Surrogate;
        });
    }
}