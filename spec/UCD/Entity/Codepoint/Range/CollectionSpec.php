<?php

namespace spec\UCD\Entity\Codepoint\Range;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Range;
use UCD\Entity\Codepoint\Range\Collection;

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

    public function it_can_be_expanded_into_all_covered_codepoints()
    {
        $range = new Range(Codepoint::fromInt(1), Codepoint::fromInt(3));

        $this->beConstructedWith(new \ArrayIterator([$range]));

        $this->expand()
            ->shouldIterateLike([Codepoint::fromInt(1), Codepoint::fromInt(2), Codepoint::fromInt(3)]);
    }

    public function it_can_be_iterated_over()
    {
        $range1 = new Range(Codepoint::fromInt(1), Codepoint::fromInt(3));
        $range2 = new Range(Codepoint::fromInt(4), Codepoint::fromInt(5));
        $ranges = [$range1, $range2];

        $this->beConstructedWith(new \ArrayIterator($ranges));

        $this->shouldIterateLike($ranges);
    }

    public function it_exposes_a_count_of_the_ranges_it_holds()
    {
        $range1 = new Range(Codepoint::fromInt(1), Codepoint::fromInt(3));
        $range2 = new Range(Codepoint::fromInt(4), Codepoint::fromInt(5));

        $this->beConstructedWith(new \ArrayIterator([$range1, $range2]));

        $this->shouldHaveCount(2);
    }
}
