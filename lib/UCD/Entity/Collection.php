<?php

namespace UCD\Entity;

interface Collection extends \IteratorAggregate, \Countable
{
    /**
     * @param callable $filter
     * @return $this
     */
    public function filterWith(callable $filter);

    /**
     * @param callable $callback
     * @return $this
     */
    public function traverseWith(callable $callback);

    /**
     * @return array
     */
    public function toArray();
}