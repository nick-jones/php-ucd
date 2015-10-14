<?php

namespace spec\UCD\Entity\Codepoint;

use PhpSpec\ObjectBehavior;

use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Range;
use UCD\Exception\InvalidRangeException;

/**
 * @mixin Range
 */
class RangeSpec extends ObjectBehavior
{
    public function it_throws_InvalidRangeException_if_the_start_codepoint_exceeds_the_end_codepoint_in_terms_of_value()
    {
        $start = Codepoint::fromInt(2);
        $end = Codepoint::fromInt(1);

        $this->beConstructedWith($start, $end);

        $this->shouldThrow(InvalidRangeException::class)
            ->duringInstantiation();
    }

    public function it_represents_a_single_codepoint_if_the_start_and_end_codepoints_are_the_same_in_terms_of_value()
    {
        $start = Codepoint::fromInt(1);
        $end = Codepoint::fromInt(1);

        $this->beConstructedWith($start, $end);

        $this->representsSingleCodepoint()
            ->shouldReturn(true);
    }

    public function it_does_not_represent_a_single_codepoint_if_the_start_and_end_codepoints_vary()
    {
        $start = Codepoint::fromInt(1);
        $end = Codepoint::fromInt(2);

        $this->beConstructedWith($start, $end);

        $this->representsSingleCodepoint()
            ->shouldReturn(false);
    }
}