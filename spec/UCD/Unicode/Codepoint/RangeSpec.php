<?php

namespace spec\UCD\Unicode\Codepoint;

use PhpSpec\ObjectBehavior;

use UCD\Unicode\Codepoint;
use UCD\Unicode\Codepoint\Range;
use UCD\Exception\InvalidRangeException;
use UCD\Unicode\TransformationFormat;

/**
 * @mixin Range
 */
class RangeSpec extends ObjectBehavior
{
    public function it_throws_InvalidRangeException_if_the_start_codepoint_exceeds_the_end_codepoint_in_terms_of_value()
    {
        $start = Codepoint::fromInt(2);
        $end = Codepoint::fromInt(1);

        $this->givenTheRangeIsBetween($start, $end);

        $this->shouldThrow(InvalidRangeException::class)
            ->duringInstantiation();
    }

    public function it_represents_a_single_codepoint_if_the_start_and_end_codepoints_are_the_same_in_terms_of_value()
    {
        $start = Codepoint::fromInt(1);
        $end = Codepoint::fromInt(1);

        $this->givenTheRangeIsBetween($start, $end);

        $this->representsSingleCodepoint()
            ->shouldReturn(true);
    }

    public function it_does_not_represent_a_single_codepoint_if_the_start_and_end_codepoints_vary()
    {
        $start = Codepoint::fromInt(1);
        $end = Codepoint::fromInt(2);

        $this->givenTheRangeIsBetween($start, $end);

        $this->representsSingleCodepoint()
            ->shouldReturn(false);
    }

    public function it_can_be_expanded_to_all_codepoints_that_it_covers()
    {
        $start = Codepoint::fromInt(1);
        $end = Codepoint::fromInt(3);

        $this->givenTheRangeIsBetween($start, $end);

        $this->expand()
            ->shouldIterateLike([Codepoint::fromInt(1), Codepoint::fromInt(2), Codepoint::fromInt(3)]);
    }

    public function it_can_be_instantiated_from_encoded_characters()
    {
        $start = 'a';
        $end = 'z';
        $encoding = TransformationFormat::ofType(TransformationFormat::EIGHT);

        $this->beConstructedThrough('betweenEncodedCharacters', [$start, $end, $encoding]);

        $this->shouldBeLike(
            Range::between(Codepoint::fromInt(97), Codepoint::fromInt(122)
        ));
    }

    private function givenTheRangeIsBetween(Codepoint $start, Codepoint $end)
    {
        $this->beConstructedThrough('between', [$start, $end]);
    }
}