<?php

namespace UCD\Entity\Codepoint;

use UCD\Entity\Codepoint;

class Collection implements \IteratorAggregate, \Countable
{
    /**
     * @var Codepoint[]|\Traversable
     */
    private $codepoints;

    /**
     * @param Codepoint[]|\Traversable $codepoints
     */
    public function __construct(\Traversable $codepoints)
    {
        $this->codepoints = $codepoints;
    }

    /**
     * @return int[]|\Traversable
     */
    public function flatten()
    {
        foreach ($this->codepoints as $codepoint) {
            yield $codepoint->getValue();
        }
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return $this->codepoints;
    }

    /**
     * @return int
     */
    public function count()
    {
        return iterator_count($this->codepoints);
    }
}