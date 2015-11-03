<?php

namespace UCD\Entity\Codepoint;

use UCD\Entity\Codepoint;
use UCD\Entity\Collection\TraversableBackedCollection;

class Collection extends TraversableBackedCollection
{
    /**
     * @return int[]|\Traversable
     */
    public function flatten()
    {
        /** @var Codepoint $codepoint */
        foreach ($this as $codepoint) {
            yield $codepoint->getValue();
        }
    }
}