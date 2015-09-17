<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use IntervalTree\NumericRangeInclusive;
use UCD\Entity\Comparable;
use UCD\Exception\InvalidArgumentException;
use UCD\Exception\InvalidRangeException;

class Range extends NumericRangeInclusive implements Comparable
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

    /**
     * @param mixed $other
     * @return bool
     */
    public function equals($other)
    {
        if ($this === $other) {
            return true;
        }

        return $other instanceof self
            && $this->start === $other->start
            && $this->end === $other->end
            && $this->step === $other->step;
    }
}