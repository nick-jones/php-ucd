<?php

namespace UCD\Entity\Character;

use UCD\Consumer\CodepointAccumulatingConsumer;
use UCD\Consumer\CodepointAggregatingConsumer;
use UCD\Consumer\Consumer;
use UCD\Consumer\ConsumerInvoker;
use UCD\Consumer\RegexBuildingConsumer;

use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Range;
use UCD\Entity\CodepointAssigned;

class Collection implements \IteratorAggregate
{
    /**
     * @var \Traversable
     */
    private $items;

    /**
     * @param \Traversable $items
     */
    public function __construct(\Traversable $items)
    {
        $this->items = $items;
    }

    /**
     * @param callable $filter
     * @return $this
     */
    public function filterWith(callable $filter)
    {
        return new static(
            $this->applyFilter($filter)
        );
    }

    /**
     * @param callable $filter
     * @return \Generator
     */
    private function applyFilter(callable $filter)
    {
        foreach ($this as $item) {
            if (call_user_func($filter, $item) === true) {
                yield $item;
            }
        }
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function traverseWith(callable $callback)
    {
        foreach ($this as $character) {
            call_user_func($callback, $character);
        }

        return $this;
    }

    /**
     * @param Consumer $consumer
     * @return $this
     */
    public function traverseWithConsumer(Consumer $consumer)
    {
        return $this->traverseWith(
            new ConsumerInvoker($consumer)
        );
    }

    /**
     * @return Codepoint\Collection|Codepoint[]
     */
    public function asCodepoints()
    {
        $consumer = new CodepointAccumulatingConsumer();
        $this->traverseWithConsumer($consumer);

        return $consumer->getCodepoints();
    }

    /**
     * @return Codepoint\Collection|Codepoint[]
     */
    public function asCodepointsLazy()
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
     * @return Range\Collection|Range[]
     */
    public function asCodepointRanges()
    {
        $consumer = new CodepointAggregatingConsumer();
        $this->traverseWithConsumer($consumer);

        return $consumer->getAggregated();
    }

    /**
     * @return string
     */
    public function asRegexCharacterClass()
    {
        $aggregator = new CodepointAggregatingConsumer();
        $consumer = new RegexBuildingConsumer($aggregator);
        $this->traverseWithConsumer($consumer);

        return $consumer->getCharacterClass();
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return $this->items;
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