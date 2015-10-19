<?php

namespace UCD\Traverser;

use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Range;
use UCD\Entity\CodepointAssigned;
use UCD\Exception\UnexpectedValueException;

class CodepointAggregator extends Traverser
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
     * {@inheritDoc}
     */
    protected function consume(CodepointAssigned $entity)
    {
        $codepoint = $entity->getCodepoint();
        $shouldCloseCurrentRange = $this->shouldCloseCurrentRangeWith($codepoint);
        $shouldOpenNewRange = !$this->hasReceivedCharacters() || $shouldCloseCurrentRange;

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
    private function hasReceivedCharacters()
    {
        return $this->previous !== null;
    }

    /**
     * @param Codepoint $codepoint
     * @return bool
     */
    private function shouldCloseCurrentRangeWith(Codepoint $codepoint)
    {
        return $this->hasReceivedCharacters()
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

        return new Range($this->rangeStart, $this->previous);
    }

    /**
     * @return Range[]
     */
    public function getAggregated()
    {
        $aggregated = $this->aggregated;

        if ($this->hasReceivedCharacters()) {
            array_push($aggregated, $this->getCurrentRange());
        }

        return $aggregated;
    }
}
