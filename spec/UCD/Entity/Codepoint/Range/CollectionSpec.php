<?php

namespace spec\UCD\Entity\Codepoint\Range;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Range;
use UCD\Entity\Codepoint\Range\Collection;
use UCD\Entity\Collection as CollectionInterface;

/**
 * @mixin Collection
 */
class CollectionSpec extends ObjectBehavior
{
    public function it_should_implement_the_collection_interface()
    {
        $this->givenTheCollectionContains([]);
        $this->shouldImplement(CollectionInterface::class);
    }

    public function it_can_be_expanded_into_all_covered_codepoints()
    {
        $range = new Range(Codepoint::fromInt(1), Codepoint::fromInt(3));

        $this->givenTheCollectionContains([$range]);

        $this->expand()
            ->shouldIterateLike([Codepoint::fromInt(1), Codepoint::fromInt(2), Codepoint::fromInt(3)]);
    }

    public function it_can_be_iterated_over()
    {
        $range1 = new Range(Codepoint::fromInt(1), Codepoint::fromInt(3));
        $range2 = new Range(Codepoint::fromInt(4), Codepoint::fromInt(5));
        $ranges = [$range1, $range2];

        $this->givenTheCollectionContains($ranges);
        $this->shouldIterateLike($ranges);
    }

    public function it_exposes_a_count_of_the_ranges_it_holds()
    {
        $range1 = new Range(Codepoint::fromInt(1), Codepoint::fromInt(3));
        $range2 = new Range(Codepoint::fromInt(4), Codepoint::fromInt(5));

        $this->givenTheCollectionContains([$range1, $range2]);
        $this->shouldHaveCount(2);
    }

    public function it_can_be_filtered_using_custom_filter_rules()
    {
        $range1 = new Range(Codepoint::fromInt(1), Codepoint::fromInt(3));
        $range2 = new Range(Codepoint::fromInt(4), Codepoint::fromInt(5));

        $filter = function (Range $range) {
            static $i = 0;
            return $i++ === 0;
        };

        $this->givenTheCollectionContains([$range1, $range2]);

        $this->filterWith($filter)
            ->shouldIterateLike([$range1]);
    }

    public function it_can_be_traversed_by_providing_a_callback()
    {
        $this->givenTheCollectionContains([new Range(Codepoint::fromInt(1), Codepoint::fromInt(3))]);
        $count = 0;
        $callback = function (Range $r) use (&$count) { ++$count; };

        $this->traverseWith($callback);

        if ($count !== 1) {
            throw new \RuntimeException();
        }
    }

    private function givenTheCollectionContains(array $ranges)
    {
        $this->beConstructedWith(new \ArrayIterator($ranges));
    }
}
