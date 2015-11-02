<?php

namespace spec\UCD\Entity\Codepoint;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Collection;

/**
 * @mixin Collection
 */
class CollectionSpec extends ObjectBehavior
{
    public function it_is_traversable()
    {
        $this->beConstructedWith(new \ArrayIterator([]));
        $this->shouldImplement(\Traversable::class);
    }

    public function it_is_countable()
    {
        $this->beConstructedWith(new \ArrayIterator([]));
        $this->shouldImplement(\Countable::class);
    }

    public function it_can_be_flattened_to_values()
    {
        $this->beConstructedWith(new \ArrayIterator([Codepoint::fromInt(1), Codepoint::fromInt(2)]));

        $this->flatten()
            ->shouldIterateLike([1, 2]);
    }

    public function it_can_be_iterated_over()
    {
        $codepoints = [Codepoint::fromInt(1), Codepoint::fromInt(2)];
        $this->beConstructedWith(new \ArrayIterator($codepoints));

        $this->shouldIterateLike($codepoints);
    }

    public function it_exposes_the_count_of_codepoints_it_holds()
    {
        $this->beConstructedWith(new \ArrayIterator([Codepoint::fromInt(1), Codepoint::fromInt(2)]));

        $this->shouldHaveCount(2);
    }
}