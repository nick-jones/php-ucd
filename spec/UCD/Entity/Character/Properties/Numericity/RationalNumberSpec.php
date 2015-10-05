<?php

namespace spec\UCD\Entity\Character\Properties\Numericity;

use PhpSpec\ObjectBehavior;
use UCD\Entity\Character\Properties\Numericity\RationalNumber;
use UCD\Exception\InvalidArgumentException;

/**
 * @mixin RationalNumber
 */
class RationalNumberSpec extends ObjectBehavior
{
    public function it_is_fractional_if_the_number_has_a_denominator_other_than_one()
    {
        $this->beConstructedWith(1, 2, false);
        $this->shouldBeFraction();
    }

    public function it_is_not_fractional_if_the_denominator_is_one()
    {
        $this->beConstructedWith(1, 1, false);
        $this->shouldNotBeFraction();
    }

    public function it_should_not_permit_a_denominator_of_zero()
    {
        $this->beConstructedWith(1, 0, false);
        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_can_be_constructed_from_a_string_representation_of_a_positive_int_value()
    {
        $this->beConstructedThrough('fromString', ['1']);
        $this->shouldBeLike(new RationalNumber(1, 1, false));
    }

    public function it_can_be_constructed_from_a_string_representation_of_a_negative_int_value()
    {
        $this->beConstructedThrough('fromString', ['-1']);
        $this->shouldBeLike(new RationalNumber(1, 1, true));
    }

    public function it_can_be_constructed_from_a_string_representation_of_a_positive_fractional_value()
    {
        $this->beConstructedThrough('fromString', ['1/5']);
        $this->shouldBeLike(new RationalNumber(1, 5, false));
    }

    public function it_can_be_constructed_from_a_string_representation_of_a_negative_fractional_value()
    {
        $this->beConstructedThrough('fromString', ['-1/5']);
        $this->shouldBeLike(new RationalNumber(1, 5, true));
    }

    public function it_can_be_converted_to_a_string_representation_of_a_positive_int_value()
    {
        $this->beConstructedWith(1, 1, false);
        $this->__toString()
            ->shouldReturn('1');
    }

    public function it_can_be_converted_to_a_string_representation_of_a_negative_int_value()
    {
        $this->beConstructedWith(1, 1, true);
        $this->__toString()
            ->shouldReturn('-1');
    }

    public function it_can_be_converted_to_a_string_representation_of_a_positive_fractional_value()
    {
        $this->beConstructedWith(1, 5, false);
        $this->__toString()
            ->shouldReturn('1/5');
    }

    public function it_can_be_converted_to_a_string_representation_of_a_negative_fractional_value()
    {
        $this->beConstructedWith(1, 5, true);
        $this->__toString()
            ->shouldReturn('-1/5');
    }
}