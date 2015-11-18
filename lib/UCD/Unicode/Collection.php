<?php

namespace UCD\Unicode;

interface Collection extends \IteratorAggregate, \Countable
{
    /**
     * @param callable $filter
     * @return static
     */
    public function filterWith(callable $filter);

    /**
     * @param callable $callback
     * @return static
     */
    public function traverseWith(callable $callback);

    /**
     * @return array
     */
    public function toArray();
}