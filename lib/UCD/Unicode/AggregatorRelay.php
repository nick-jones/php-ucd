<?php

namespace UCD\Unicode;

use UCD\Exception\InvalidArgumentException;
use UCD\Unicode\AggregatorRelay\KeyGenerator;
use UCD\Unicode\Codepoint\Aggregator;

class AggregatorRelay
{
    /**
     * @var KeyGenerator
     */
    private $keyGenerator;

    /**
     * @var \ReflectionClass
     */
    private $aggregatorClass;

    /**
     * @var Aggregator[]
     */
    private $aggregators = [];

    /**
     * @param KeyGenerator $keyGenerator
     * @param \ReflectionClass $aggregatorClass
     */
    public function __construct(KeyGenerator $keyGenerator, \ReflectionClass $aggregatorClass)
    {
        $this->keyGenerator = $keyGenerator;
        $this->aggregatorClass = $aggregatorClass;
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
            $this->aggregators[$key] = $this->aggregatorClass->newInstance();
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
}