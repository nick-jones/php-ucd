<?php

namespace UCD\Entity\Character;

use UCD\Consumer\Consumer;
use UCD\Consumer\ConsumerInvoker;

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