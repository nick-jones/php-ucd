<?php

namespace spec\UCD\Consumer;

use PhpSpec\ObjectBehavior;

use UCD\Consumer\Consumer;
use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;

use UCD\Consumer\CodepointAccumulatingConsumer;

/**
 * @mixin CodepointAccumulatingConsumer
 */
class CodepointAccumulatingConsumerSpec extends ObjectBehavior
{
    public function it_is_a_consumer()
    {
        $this->shouldImplement(Consumer::class);
    }

    public function it_accumulates_codepoints_for_all_provided_entities(CodepointAssigned $c1, CodepointAssigned $c2)
    {
        $this->givenEntityHasCodepointWithValue($c1, 1);
        $this->givenEntityHasCodepointWithValue($c2, 2);

        $this->consume($c1);
        $this->consume($c2);

        $this->getCodepoints()
            ->shouldBeLike([Codepoint::fromInt(1), Codepoint::fromInt(2)]);
    }

    private function givenEntityHasCodepointWithValue(CodepointAssigned $entity, $value)
    {
        $entity->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));
    }
}