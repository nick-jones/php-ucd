<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use UCD\Exception\UnexpectedValueException;
use UCD\Unicode\Codepoint\AggregatorRelay;

class PropertyAggregators implements \IteratorAggregate
{
    /**
     * @var \SplObjectStorage
     */
    private $map;

    public function __construct()
    {
        $this->map = new \SplObjectStorage();
    }

    /**
     * @param Property $property
     * @param AggregatorRelay $aggregatorRelay
     */
    public function registerAggregatorRelay(Property $property, AggregatorRelay $aggregatorRelay)
    {
        $this->map->attach($property, $aggregatorRelay);
    }

    /**
     * @param array $characters
     */
    public function addCharacters(array $characters)
    {
        foreach ($this->map as $key) {
            $aggregator = $this->map->offsetGet($key);
            $aggregator->addMany($characters);
        }
    }

    /**
     * @param Property $property
     * @return AggregatorRelay
     * @throws UnexpectedValueException
     */
    public function getByProperty(Property $property)
    {
        if (!$this->map->offsetExists($property)) {
            throw new UnexpectedValueException();
        }

        return $this->map->offsetGet($property);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        foreach ($this->map as $key) {
            yield $key => $this->map->offsetGet($key);
        }
    }
}