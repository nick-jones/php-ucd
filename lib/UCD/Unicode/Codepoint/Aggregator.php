<?php

namespace UCD\Unicode\Codepoint;

use UCD\Unicode\Codepoint;
use UCD\Unicode\Codepoint\Range;
use UCD\Exception\UnexpectedValueException;

class Aggregator
{
    /**
     * @var Range[]
     */
    private $aggregated = [];

    /**
     * @var Codepoint|null
     */
    private $previous;

    /**
     * @var Codepoint|null
     */
    private $rangeStart;

    /**
     * @param Codepoint $codepoint
     */
    public function addCodepoint(Codepoint $codepoint)
    {
        $shouldCloseCurrentRange = $this->shouldCloseCurrentRangeWith($codepoint);
        $shouldOpenNewRange = !$this->hasOpenRange() || $shouldCloseCurrentRange;

        if ($shouldCloseCurrentRange) {
            $this->closeCurrentRange();
        }

        if ($shouldOpenNewRange) {
            $this->openNewRangeWith($codepoint);
        }

        $this->previous = $codepoint;
    }

    /**
     * @return bool
     */
    private function hasOpenRange()
    {
        return $this->previous !== null;
    }

    /**
     * @param Codepoint $codepoint
     * @return bool
     */
    private function shouldCloseCurrentRangeWith(Codepoint $codepoint)
    {
        return $this->hasOpenRange()
            && !$this->isPlusOneOfPrevious($codepoint);
    }

    /**
     * @param Codepoint $codepoint
     * @return bool
     * @throws UnexpectedValueException
     */
    private function isPlusOneOfPrevious(Codepoint $codepoint)
    {
        if ($this->previous === null) {
            throw new UnexpectedValueException('Previous cannot be NULL');
        }

        return $this->previous->getValue() + 1 === $codepoint->getValue();
    }

    /**
     * @param Codepoint $codepoint
     */
    private function openNewRangeWith(Codepoint $codepoint)
    {
        $this->rangeStart = $codepoint;
    }

    /**
     * @param Range $range
     */
    private function addRange(Range $range)
    {
        array_push($this->aggregated, $range);
    }

    private function closeCurrentRange()
    {
        $this->addRange($this->getCurrentRange());
        $this->rangeStart = null;
    }

    /**
     * @return Range
     * @throws UnexpectedValueException
     */
    private function getCurrentRange()
    {
        if ($this->rangeStart === null || $this->previous === null) {
            throw new UnexpectedValueException('Range values cannot be NULL');
        }

        return Range::between($this->rangeStart, $this->previous);
    }

    /**
     * @return Range[]|Range\Collection
     */
    public function getAggregated()
    {
        $aggregated = $this->aggregated;

        if ($this->hasOpenRange()) {
            array_push($aggregated, $this->getCurrentRange());
        }

        return new Range\Collection(
            new \ArrayIterator($aggregated)
        );
    }

    /**
     * @return bool
     */
    public function hasAggregated()
    {
        return count($this->getAggregated()) > 0;
    }
}
