<?php

namespace spec\UCD\Entity\Character;

use PhpSpec\ObjectBehavior;
use UCD\Entity\Character\Codepoint;
use UCD\Entity\Comparable;
use UCD\Exception\InvalidArgumentException;
use UCD\Exception\OutOfRangeException;

/**
 * @mixin Codepoint
 */
class CodepointSpec extends ObjectBehavior
{
    public function it_should_throw_an_OutOfRangeException_if_the_codepoint_less_than_zero()
    {
        $this->beConstructedThrough('fromInt', [-1]);
        $this->shouldThrow(OutOfRangeException::CLASS);
    }

    public function it_should_throw_an_OutOfRangeException_if_the_codepoint_greater_than_0x10FFFF()
    {
        $this->beConstructedThrough('fromInt', [0x110000]);
        $this->shouldThrow(OutOfRangeException::CLASS);
    }

    public function it_should_throw_an_InvalidArgumentException_if_value_is_a_non_integer()
    {
        $this->beConstructedThrough('fromInt', [0x110000]);
        $this->shouldThrow(InvalidArgumentException::CLASS);
    }

    public function it_should_expose_its_codepoint_value_as_an_integer()
    {
        $this->beConstructedThrough('fromInt', [0x10]);

        $this->getValue()
            ->shouldReturn(0x10);
    }

    public function it_can_be_created_from_a_hex_string()
    {
        $this->beConstructedThrough('fromHex', ['10']);

        $this->getValue()
            ->shouldReturn(0x10);
    }

    public function it_should_be_comparable()
    {
        $this->beConstructedThrough('fromInt', [0x10]);
        $this->shouldHaveType(Comparable::CLASS);
    }

    public function it_should_equal_an_instance_with_the_same_value()
    {
        $this->beConstructedThrough('fromInt', [0x10]);

        $this->equals(Codepoint::fromInt(0x10))
            ->shouldReturn(true);
    }

    public function it_should_not_equal_an_instance_with_a_different_value()
    {
        $this->beConstructedThrough('fromInt', [0x11]);

        $this->equals(Codepoint::fromInt(0x10))
            ->shouldReturn(false);
    }

    public function it_should_be_possible_to_cast_it_to_a_string()
    {
        $this->beConstructedThrough('fromInt', [0xA]);

        $this->__toString()
            ->shouldReturn('U+A');
    }
}