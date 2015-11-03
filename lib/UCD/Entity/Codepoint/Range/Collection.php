<?php

namespace UCD\Entity\Codepoint\Range;

use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Range;
use UCD\Entity\Collection\TraversableBackedCollection;

class Collection extends TraversableBackedCollection
{
    /**
     * @return Codepoint[]|Codepoint\Collection
     */
    public function expand()
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
        /** @var Range $range */
        foreach ($this as $range) {
            foreach ($range->expand() as $codepoint) {
                yield $codepoint;
            }
        }
    }
}
