<?php

namespace UCD\Unicode\Collection;

use UCD\Unicode\Collection;

abstract class TraversableBackedCollection implements SnapshotCapableCollection
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function traverseWith(callable $callback)
    {
        foreach ($this as $character) {
            call_user_func($callback, $character);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function takeSnapshot()
    {
        return new static(
            new \ArrayIterator($this->toArray())
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return $this->items;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return iterator_count($this->items);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return iterator_to_array($this->items);
    }

    /**
     * @param array $items
     * @return Collection
     */
    public static function fromArray(array $items)
    {
        return new static(
            new \ArrayIterator($items)
        );
    }
}