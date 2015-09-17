<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use IntervalTree\IntervalTree as BaseIntervalTree;

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

        parent::__construct($ranges, $comparator);
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