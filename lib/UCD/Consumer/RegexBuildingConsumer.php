<?php

namespace UCD\Consumer;

use UCD\Entity\Codepoint\Range;
use UCD\Entity\CodepointAssigned;

class RegexBuildingConsumer implements Consumer
{
    /**
     * @var CodepointAggregatingConsumer
     */
    private $aggregator;

    /**
     * @param CodepointAggregatingConsumer $aggregator
     */
    public function __construct(CodepointAggregatingConsumer $aggregator)
    {
        $this->aggregator = $aggregator;
    }

    /**
     * @param CodepointAssigned $entity
     */
    public function consume(CodepointAssigned $entity)
    {
        $this->aggregator->consume($entity);
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
     * @param Range[]|Range\Collection $ranges
     * @return string[]
     */
    private function flattenRanges(Range\Collection $ranges)
    {
        $flattened = [];

        foreach ($ranges as $range) {
            array_push($flattened, $this->flattenRange($range));
        }

        return $flattened;
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