<?php

namespace UCD\Unicode\Codepoint;

use UCD\Exception\InvalidArgumentException;

use UCD\Unicode\Codepoint\Aggregator\Factory;
use UCD\Unicode\Codepoint\AggregatorRelay\KeyGenerator;
use UCD\Unicode\CodepointAssigned;

class AggregatorRelay
{
    /**
     * @var KeyGenerator
     */
    private $keyGenerator;

    /**
     * @var Aggregator[]
     */
    private $aggregators = [];

    /**
     * @param KeyGenerator $keyGenerator
     */
    public function __construct(KeyGenerator $keyGenerator)
    {
        $this->keyGenerator = $keyGenerator;
    }

    /**
     * @param CodepointAssigned $entity
     * @throws InvalidArgumentException
     */
    public function add(CodepointAssigned $entity)
    {
        $aggregator = $this->getAggregatorFor($entity);
        $aggregator->addCodepoint($entity->getCodepoint());
    }

    /**
     * @param CodepointAssigned[] $entities
     */
    public function addMany(array $entities)
    {
        foreach ($entities as $entity) {
            $this->add($entity);
        }
    }

    /**
     * @param CodepointAssigned $entity
     * @return Aggregator
     */
    protected function getAggregatorFor(CodepointAssigned $entity)
    {
        $key = $this->keyGenerator->generateFor($entity);

        if (!array_key_exists($key, $this->aggregators)) {
            $this->aggregators[$key] = new Aggregator();
        }

        return $this->aggregators[$key];
    }

    /**
     * @return Range\Collection[]
     */
    public function getAllRanges()
    {
        $ranges = [];

        foreach ($this->aggregators as $key => $aggregator) {
            $ranges[$key] = $aggregator->getAggregated();
        }

        return $ranges;
    }
}