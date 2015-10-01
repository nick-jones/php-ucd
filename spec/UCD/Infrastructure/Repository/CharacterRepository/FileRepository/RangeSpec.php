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

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_cannot_be_instantiated_with_a_non_numeric_end_value()
    {
        $this->beConstructedWith(1, 'a');

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_cannot_be_instantiated_with_a_start_value_greater_than_the_end_value()
    {
        $this->beConstructedWith(10, 1);

        $this->shouldThrow(InvalidRangeException::class)
            ->duringInstantiation();
    }
}