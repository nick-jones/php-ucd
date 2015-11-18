<?php

namespace spec\UCD\Unicode\Codepoint;

use PhpSpec\ObjectBehavior;

use UCD\Unicode\Codepoint;
use UCD\Unicode\Codepoint\Aggregator;
use UCD\Unicode\Codepoint\Range;

/**
 * @mixin Aggregator
 */
class AggregatorSpec extends ObjectBehavior
{
    public function it_exposes_an_empty_array_if_no_characters_have_been_provided()
    {
        $this->getAggregated()
            ->shouldIterateLike([]);
    }

    public function it_can_aggregate_mixtures_of_ranges_and_individual_codepoints()
    {
        $this->addCodepoint(Codepoint::fromInt(1));
        $this->addCodepoint(Codepoint::fromInt(2));
        $this->addCodepoint(Codepoint::fromInt(3));
        $this->addCodepoint(Codepoint::fromInt(10));
        $this->addCodepoint(Codepoint::fromInt(11));
        $this->addCodepoint(Codepoint::fromInt(20));

        $this->getAggregated()
            ->shouldIterateLike([
                Range::between(Codepoint::fromInt(1), Codepoint::fromInt(3)),
                Range::between(Codepoint::fromInt(10), Codepoint::fromInt(11)),
                Range::between(Codepoint::fromInt(20), Codepoint::fromInt(20))
            ]);
    }
}