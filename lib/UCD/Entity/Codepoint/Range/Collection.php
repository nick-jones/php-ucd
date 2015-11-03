<?php

namespace UCD\Entity\Codepoint\Range;

use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Range;

class Collection implements \IteratorAggregate, \Countable
{
    /**
     * @var \Traversable|Range[]
     */
    private $ranges;

    /**
     * @param Range[]|\Traversable $ranges
     */
    public function __construct(\Traversable $ranges)
    {
        $this->ranges = $ranges;
    }

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
        foreach ($this->ranges as $range) {
            foreach ($range->expand() as $codepoint) {
                yield $codepoint;
            }
        }
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return $this->ranges;
    }

    /**
     * @return int
     */
    public function count()
    {
        return iterator_count($this->ranges);
    }
}
