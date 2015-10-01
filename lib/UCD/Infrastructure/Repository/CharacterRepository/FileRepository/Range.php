<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use IntervalTree\NumericRangeInclusive;

use UCD\Exception\InvalidArgumentException;
use UCD\Exception\InvalidRangeException;

class Range extends NumericRangeInclusive
{
    /**
     * @param int $start
     * @param int $end
     * @throws InvalidArgumentException
     * @throws InvalidRangeException
     */
    public function __construct($start, $end)
    {
        if (!is_int($start)) {
            throw new InvalidArgumentException();
        }

        if (!is_int($end)) {
            throw new InvalidArgumentException();
        }

        if ($start > $end) {
            throw new InvalidRangeException();
        }

        parent::__construct($start, $end, 1);
    }
}