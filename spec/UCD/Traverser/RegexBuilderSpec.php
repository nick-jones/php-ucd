<?php

namespace spec\UCD\Traverser;

use PhpSpec\ObjectBehavior;

use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;
use UCD\Traverser\CodepointAggregator;

class RegexBuilderSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(new CodepointAggregator());
    }

    public function it_is_invokable()
    {
        $this->shouldBeInvokable();
    }

    public function it_returns_an_empty_string_if_no_characters_have_been_provided()
    {
        $this->getCharacterClass()
            ->shouldReturn('');
    }

    public function it_returns_character_class_including_ranges_for_provided_characters(
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
            $this($character);
        }

        $this->getCharacterClass()
            ->shouldEqual('[\x{1}-\x{3}\x{A}-\x{B}\x{14}]');
    }

    private function givenEntityHasCodepointWithValue(CodepointAssigned $entity, $value)
    {
        $entity->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));
    }
}