<?php

namespace spec\UCD\Traverser;

use PhpSpec\ObjectBehavior;

use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;

use UCD\Traverser\CodepointAccumulator;

/**
 * @mixin CodepointAccumulator
 */
class CodepointAccumulatorSpec extends ObjectBehavior
{
    public function it_is_invokable()
    {
        $this->shouldBeInvokable();
    }

    public function it_accumulates_codepoints_for_all_provided_entities(CodepointAssigned $c1, CodepointAssigned $c2)
    {
        $this->givenEntityHasCodepointWithValue($c1, 1);
        $this->givenEntityHasCodepointWithValue($c2, 2);

        $this($c1);
        $this($c2);

        $this->getCodepoints()
            ->shouldBeLike([Codepoint::fromInt(1), Codepoint::fromInt(2)]);
    }

    private function givenEntityHasCodepointWithValue(CodepointAssigned $entity, $value)
    {
        $entity->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));
    }
}