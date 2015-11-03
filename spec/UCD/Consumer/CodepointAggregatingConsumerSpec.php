<?php

namespace spec\UCD\Consumer;

use PhpSpec\ObjectBehavior;

use UCD\Consumer\Consumer;
use UCD\Entity\CodepointAssigned;
use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Range;

use UCD\Consumer\CodepointAggregatingConsumer;

/**
 * @mixin CodepointAggregatingConsumer
 */
class CodepointAggregatingConsumerSpec extends ObjectBehavior
{
    public function it_is_a_consumer()
    {
        $this->shouldImplement(Consumer::class);
    }

    public function it_exposes_an_empty_array_if_no_characters_have_been_provided()
    {
        $this->getAggregated()
            ->shouldIterateLike([]);
    }

    public function it_can_aggregate_mixtures_of_ranges_and_individual_codepoints(
        CodepointAssigned $c1,
        CodepointAssigned $c2,
        CodepointAssigned $c3,
        CodepointAssigned $c4,
        CodepointAssigned $c5,
        CodepointAssigned $c6
    ) {
        $this->givenEntityHasCodepointWithValue($c1, 1);
        $this->givenEntityHasCodepointWithValue($c2, 2);
        $this->givenEntityHasCodepointWithValue($c3, 3);
        $this->givenEntityHasCodepointWithValue($c4, 10);
        $this->givenEntityHasCodepointWithValue($c5, 11);
        $this->givenEntityHasCodepointWithValue($c6, 20);

        foreach ([$c1, $c2, $c3, $c4, $c5, $c6] as $character) {
            $this->consume($character);
        }

        $this->getAggregated()
            ->shouldIterateLike([
                Range::between(Codepoint::fromInt(1), Codepoint::fromInt(3)),
                Range::between(Codepoint::fromInt(10), Codepoint::fromInt(11)),
                Range::between(Codepoint::fromInt(20), Codepoint::fromInt(20))
            ]);
    }

    private function givenEntityHasCodepointWithValue(CodepointAssigned $entity, $value)
    {
        $entity->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));
    }
}