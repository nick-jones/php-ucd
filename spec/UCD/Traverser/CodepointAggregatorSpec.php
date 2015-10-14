<?php

namespace spec\UCD\Traverser;

use PhpSpec\ObjectBehavior;

use UCD\Entity\Character;
use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Range;

use UCD\Traverser\CodepointAggregator;

/**
 * @mixin CodepointAggregator
 */
class CodepointAggregatorSpec extends ObjectBehavior
{
    public function it_is_invokable()
    {
        $this->shouldBeInvokable();
    }

    public function it_exposes_an_empty_array_if_no_characters_have_been_provided()
    {
        $this->getAggregated()
            ->shouldReturn([]);
    }

    public function it_can_aggregate_mixtures_of_ranges_and_individual_codepoints(
        Character $c1,
        Character $c2,
        Character $c3,
        Character $c4,
        Character $c5,
        Character $c6,
        Character $c7,
        Character $c8
    ) {
        $this->givenCharacterHasCodepointWithValue($c1, 1);
        $this->givenCharacterHasCodepointWithValue($c2, 2);
        $this->givenCharacterHasCodepointWithValue($c3, 3);
        $this->givenCharacterHasCodepointWithValue($c4, 10);
        $this->givenCharacterHasCodepointWithValue($c5, 11);
        $this->givenCharacterHasCodepointWithValue($c6, 20);
        $this->givenCharacterHasCodepointWithValue($c7, 30);
        $this->givenCharacterHasCodepointWithValue($c8, 31);

        foreach ([$c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8] as $character) {
            $this($character);
        }

        $this->getAggregated()
            ->shouldBeLike([
                new Range(Codepoint::fromInt(1), Codepoint::fromInt(3)),
                new Range(Codepoint::fromInt(10), Codepoint::fromInt(11)),
                new Range(Codepoint::fromInt(20), Codepoint::fromInt(20)),
                new Range(Codepoint::fromInt(30), Codepoint::fromInt(31))
            ]);
    }

    public function getMatchers()
    {
        return [
            'beInvokable' => function ($subject) {
                return is_callable([$subject, '__invoke']);
            }
        ];
    }

    private function givenCharacterHasCodepointWithValue(Character $character, $value)
    {
        $character->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));

        $character->getCodepointValue()
            ->willReturn($value);
    }
}