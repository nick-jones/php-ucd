<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

use IntervalTree\IntervalTree as BaseIntervalTree;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;

class IntervalTree extends BaseIntervalTree
{
    /**
     * @var Range[]
     */
    protected $ranges = [];

    /**
     * @param Range[] $ranges
     * @param callable|null $comparator
     */
    public function __construct(array $ranges, callable $comparator = null)
    {
        $this->ranges = $ranges;

        BaseIntervalTree::__construct($ranges, $comparator);
    }

    /**
     * @param Range $range
     */
    public function add(Range $range)
    {
        $this->ranges[] = $range;
        $this->top_node = $this->divide_intervals($this->ranges);
    }
}