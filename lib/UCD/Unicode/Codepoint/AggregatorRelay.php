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
     * @var Factory
     */
    private $aggregatorFactory;

    /**
     * @param KeyGenerator $keyGenerator
     * @param Factory $aggregatorFactory
     */
    public function __construct(KeyGenerator $keyGenerator, Factory $aggregatorFactory)
    {
        $this->keyGenerator = $keyGenerator;
        $this->aggregatorFactory = $aggregatorFactory;
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
            $this->aggregators[$key] = $this->aggregatorFactory->create();
        }

        return $this->aggregators[$key];
    }

    /**
     * @return Aggregator[]
     */
    public function getAll()
    {
        return $this->aggregators;
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