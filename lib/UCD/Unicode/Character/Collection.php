<?php

namespace UCD\Unicode\Character;

use UCD\Unicode\Codepoint;
use UCD\Unicode\Codepoint\Range;
use UCD\Unicode\CodepointAssigned;
use UCD\Unicode\Collection\TraversableBackedCollection;

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
}