<?php

namespace spec\UCD\Unicode;

use PhpSpec\ObjectBehavior;
use UCD\Unicode\AggregatorRelay;
use UCD\Unicode\AggregatorRelay\KeyGenerator;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Codepoint\Aggregator;
use UCD\Unicode\CodepointAssigned;

/**
 * @mixin AggregatorRelay
 */
class AggregatorRelaySpec extends ObjectBehavior
{
    public function it_relays_codepoint_additions_to_the_appropriate_aggregator(
        KeyGenerator $keyGenerator,
        Aggregator $aggregator,
        CodepointAssigned $item,
        Aggregator\Factory $factory
    ) {
        $codepoint = Codepoint::fromInt(1);

        $factory->create()
            ->willReturn($aggregator);

        $keyGenerator->generateFor($item)
            ->willReturn('key');

        $item->getCodepoint()
            ->willReturn($codepoint);

        $aggregator->addCodepoint($codepoint)
            ->shouldBeCalled();

        $this->beConstructedWith($keyGenerator, $factory);
        $this->add($item);
    }

    public function it_exposes_all_aggregators(
        KeyGenerator $keyGenerator,
        Aggregator $aggregator,
        CodepointAssigned $item,
        Aggregator\Factory $factory
    ) {
        $key = 'key';

        $factory->create()
            ->willReturn($aggregator);

        $item->getCodepoint()
            ->willReturn(Codepoint::fromInt(1));

        $keyGenerator->generateFor($item)
            ->willReturn($key);

        $this->beConstructedWith($keyGenerator, $factory);
        $this->add($item);

        $this->getAll()
            ->shouldReturn([$key => $aggregator]);
    }
}