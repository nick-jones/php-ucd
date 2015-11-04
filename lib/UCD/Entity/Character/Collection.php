<?php

namespace UCD\Entity\Character;

use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Range;
use UCD\Entity\CodepointAssigned;
use UCD\Entity\Collection\TraversableBackedCollection;

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
     * @param array $items
     * @return Collection
     */
    public static function fromArray(array $items)
    {
        return new self(
            new \ArrayIterator($items)
        );
    }
}