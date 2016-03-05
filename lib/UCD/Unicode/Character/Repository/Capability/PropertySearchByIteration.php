<?php

namespace UCD\Unicode\Character\Repository\Capability;

use UCD\Exception;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Character\Properties\General\Script;
use UCD\Unicode\Character\Repository\BlockNotFoundException;
use UCD\Unicode\Character\Repository\GeneralCategoryNotFoundException;
use UCD\Unicode\Character\Repository\ScriptNotFoundException;
use UCD\Unicode\Codepoint\Aggregator;
use UCD\Unicode\Codepoint\Range;
use UCD\Unicode\CodepointAssigned;

trait PropertySearchByIteration
{
    /**
     * {@inheritDoc}
     */
    public function getCodepointsByBlock(Block $block)
    {
        $comparator = function (CodepointAssigned $item) use ($block) {
            return $item->getGeneralProperties()
                ->getBlock()
                ->equals($block);
        };

        return $this->aggregatePropertiesWith(
            $comparator,
            BlockNotFoundException::withBlock($block)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getCodepointsByCategory(GeneralCategory $category)
    {
        $comparator = function (CodepointAssigned $item) use ($category) {
            return $item->getGeneralProperties()
                ->getGeneralCategory()
                ->equals($category);
        };

        return $this->aggregatePropertiesWith(
            $comparator,
            GeneralCategoryNotFoundException::withCategory($category)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getCodepointsByScript(Script $script)
    {
        $comparator = function (CodepointAssigned $item) use ($script) {
            return $item->getGeneralProperties()
                ->getScript()
                ->equals($script);
        };

        return $this->aggregatePropertiesWith(
            $comparator,
            ScriptNotFoundException::withScript($script)
        );
    }

    /**
     * @param callable $comparator
     * @param Exception $notFoundException
     * @return Range[]|Range\Collection
     * @throws Exception
     */
    private function aggregatePropertiesWith(callable $comparator, Exception $notFoundException)
    {
        $aggregator = new Aggregator();

        foreach ($this->getAll() as $item) {
            if (call_user_func($comparator, $item) === true) {
                $codepoint = $item->getCodepoint();
                $aggregator->addCodepoint($codepoint);
            }
        }

        if ($aggregator->hasAggregated() !== true) {
            throw $notFoundException;
        }

        return $aggregator->getAggregated();
    }

    /**
     * @return CodepointAssigned[]
     */
    abstract public function getAll();
}