<?php

namespace UCD\Traverser;

use UCD\Entity\Codepoint\Range;
use UCD\Entity\CodepointAssigned;

class RegexBuilder extends Traverser
{
    /**
     * @var CodepointAggregator
     */
    private $aggregator;

    /**
     * @param CodepointAggregator $aggregator
     */
    public function __construct(CodepointAggregator $aggregator)
    {
        $this->aggregator = $aggregator;
    }

    /**
     * @param CodepointAssigned $entity
     */
    protected function consume(CodepointAssigned $entity)
    {
        $this->aggregator->__invoke($entity);
    }

    /**
     * @return string
     */
    public function getCharacterClass()
    {
        $ranges = $this->aggregator->getAggregated();

        if (count($ranges) === 0) {
            return '';
        }

        $flattened = $this->flattenRanges($ranges);

        return sprintf('[%s]', implode('', $flattened));
    }

    /**
     * @param Range[] $ranges
     * @return string[]
     */
    private function flattenRanges(array $ranges)
    {
        $mapper = function (Range $range) {
            return $this->flattenRange($range);
        };

        return array_map($mapper, $ranges);
    }

    /**
     * @param Range $range
     * @return string
     */
    private function flattenRange(Range $range)
    {
        $start = $range->getStart();
        $end = $range->getEnd();

        return $range->representsSingleCodepoint()
            ? sprintf('\x{%X}', $start->getValue())
            : sprintf('\x{%X}-\x{%X}', $start->getValue(), $end->getValue());
    }
}