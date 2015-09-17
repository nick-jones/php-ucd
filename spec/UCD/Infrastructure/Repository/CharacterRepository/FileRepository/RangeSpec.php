<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use PhpSpec\ObjectBehavior;
use UCD\Exception\InvalidArgumentException;
use UCD\Exception\InvalidRangeException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;

/**
 * @mixin Range
 */
class RangeSpec extends ObjectBehavior
{
    public function it_cannot_be_instantiated_with_a_non_numeric_start_value()
    {
        $this->beConstructedWith('a', 10);

        $this->shouldThrow(InvalidArgumentException::CLASS)
            ->duringInstantiation();
    }

    public function it_cannot_be_instantiated_with_a_non_numeric_end_value()
    {
        $this->beConstructedWith(1, 'a');

        $this->shouldThrow(InvalidArgumentException::CLASS)
            ->duringInstantiation();
    }

    public function it_cannot_be_instantiated_with_a_start_value_greater_than_the_end_value()
    {
        $this->beConstructedWith(10, 1);

        $this->shouldThrow(InvalidRangeException::CLASS)
            ->duringInstantiation();
    }

    public function it_should_equal_an_instance_with_the_same_range_values()
    {
        $this->beConstructedWith(1, 10);

        $this->equals(new Range(1, 10))
            ->shouldReturn(true);
    }

    public function it_should_not_equal_an_instance_with_different_range_values()
    {
        $this->beConstructedWith(1, 10);

        $this->equals(new Range(1, 11))
            ->shouldReturn(false);
    }
}