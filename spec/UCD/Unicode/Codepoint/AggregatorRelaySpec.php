<?php

namespace spec\UCD\Unicode\Codepoint;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use UCD\Unicode\Codepoint;
use UCD\Unicode\Codepoint\AggregatorRelay;
use UCD\Unicode\Codepoint\AggregatorRelay\KeyGenerator;
use UCD\Unicode\Codepoint\Range;
use UCD\Unicode\CodepointAssigned;

/**
 * @mixin AggregatorRelay
 */
class AggregatorRelaySpec extends ObjectBehavior
{
    public function it_exposes_all_ranges_built_by_aggregators(
        KeyGenerator $keyGenerator,
        CodepointAssigned $item
    ) {
        $key = 'key';
        $cp = Codepoint::fromInt(1);

        $item->getCodepoint()
            ->willReturn($cp);

        $keyGenerator->generateFor($item)
            ->willReturn($key);

        $this->beConstructedWith($keyGenerator);
        $this->add($item);

        $this->getAllRanges()
            ->shouldBeLike([$key => Range\Collection::fromArray([Range::between($cp, $cp)])]);
    }
}