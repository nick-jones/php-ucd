<?php

namespace UCD\Unicode\Character\Repository\Capability;

use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Codepoint\Aggregator;
use UCD\Unicode\CodepointAssigned;

trait BlockSearchByIteration
{
    /**
     * {@inheritDoc}
     */
    public function getCodepointsByBlock(Block $block)
    {
        $aggregator = new Aggregator();

        foreach ($this->getAll() as $item) {
            $generalProperties = $item->getGeneralProperties();

            if ($generalProperties->getBlock()->equals($block)) {
                $codepoint = $item->getCodepoint();
                $aggregator->addCodepoint($codepoint);
            }
        }

        return $aggregator->getAggregated();
    }

    /**
     * @return CodepointAssigned[]
     */
    abstract public function getAll();
}