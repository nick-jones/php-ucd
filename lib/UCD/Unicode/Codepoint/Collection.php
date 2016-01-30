<?php

namespace UCD\Unicode\Codepoint;

use UCD\Unicode\Codepoint;
use UCD\Unicode\Codepoint\Range\RangeRegexBuilder;
use UCD\Unicode\Collection\TraversableBackedCollection;

class Collection extends TraversableBackedCollection
{
    /**
     * @return \Traversable|int[]
     */
    public function flatten()
    {
        /** @var Codepoint $codepoint */
        foreach ($this as $codepoint) {
            yield $codepoint->getValue();
        }
    }

    /**
     * @param Codepoint $codepoint
     * @return bool
     */
    public function has(Codepoint $codepoint)
    {
        foreach ($this as $check) {
            if ($codepoint->equals($check)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Range[]|Range\Collection
     */
    public function aggregate()
    {
        $aggregator = new Aggregator();

        $this->traverseWith(function (Codepoint $codepoint) use ($aggregator) {
            $aggregator->addCodepoint($codepoint);
        });

        return $aggregator->getAggregated();
    }

    /**
     * @return string
     */
    public function toRegexCharacterClass()
    {
        $builder = new RegexBuilder();

        $this->traverseWith(function (Codepoint $codepoint) use ($builder) {
            $builder->addCodepoint($codepoint);
        });

        return $builder->getCharacterClass();
    }
}