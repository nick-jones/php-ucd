<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use PhpSpec\ObjectBehavior;
use UCD\Exception\UnexpectedValueException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Property;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyAggregators;
use UCD\Unicode\Codepoint\AggregatorRelay;

/**
 * @mixin PropertyAggregators
 */
class PropertyAggregatorsSpec extends ObjectBehavior
{
    public function it_can_resolve_an_aggregator_relay_by_property(AggregatorRelay $aggregatorRelay)
    {
        $property = Property::ofType(Property::BLOCK);
        $this->givenCollectionContains($property, $aggregatorRelay);

        $this->getByProperty($property)
            ->shouldReturn($aggregatorRelay);
    }

    public function it_throws_UnexpectedValueException_if_the_collection_lacks_the_supplied_property()
    {
        $property = Property::ofType(Property::BLOCK);

        $this->shouldThrow(UnexpectedValueException::class)
            ->duringGetByProperty($property);
    }

    public function it_can_be_iterated(AggregatorRelay $aggregatorRelay)
    {
        $property = Property::ofType(Property::BLOCK);
        $this->givenCollectionContains($property, $aggregatorRelay);

        $this->shouldYieldFromIteratorAggregate($property, $aggregatorRelay);
    }

    private function givenCollectionContains(Property $property, AggregatorRelay $aggregatorRelay)
    {
        $this->registerAggregatorRelay($property, $aggregatorRelay);
    }
}