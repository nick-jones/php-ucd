<?php

namespace spec\UCD\Entity\Codepoint;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Collection;
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

    public function it_can_be_flattened_to_values()
    {
        $this->givenTheCollectionContains([Codepoint::fromInt(1), Codepoint::fromInt(2)]);

        $this->flatten()
            ->shouldIterateLike([1, 2]);
    }

    public function it_can_be_iterated_over()
    {
        $codepoints = [Codepoint::fromInt(1), Codepoint::fromInt(2)];

        $this->givenTheCollectionContains($codepoints);
        $this->shouldIterateLike($codepoints);
    }

    public function it_exposes_the_count_of_codepoints_it_holds()
    {
        $this->givenTheCollectionContains([Codepoint::fromInt(1), Codepoint::fromInt(2)]);
        $this->shouldHaveCount(2);
    }

    public function it_can_be_filtered_using_custom_filter_rules()
    {
        $filter = function (Codepoint $c) {
            static $i = 0;
            return $i++ === 0;
        };

        $this->givenTheCollectionContains([Codepoint::fromInt(1), Codepoint::fromInt(3)]);

        $this->filterWith($filter)
            ->shouldIterateLike([Codepoint::fromInt(1)]);
    }

    public function it_can_be_traversed_by_providing_a_callback()
    {
        $this->givenTheCollectionContains([Codepoint::fromInt(1)]);
        $count = 0;
        $callback = function (Codepoint $c) use (&$count) { ++$count; };

        $this->traverseWith($callback);

        if ($count !== 1) {
            throw new \RuntimeException();
        }
    }

    public function it_can_be_aggregated_to_codepoint_ranges()
    {
        $this->givenTheCollectionContains([
            Codepoint::fromInt(1),
            Codepoint::fromInt(2),
            Codepoint::fromInt(33)
        ]);

        $this->aggregate()
            ->shouldIterateLike([
                Codepoint\Range::between(Codepoint::fromInt(1), Codepoint::fromInt(2)),
                Codepoint\Range::between(Codepoint::fromInt(33), Codepoint::fromInt(33)),
            ]);
    }

    public function it_can_be_reduced_to_a_regular_expression_character_class()
    {
        $this->givenTheCollectionContains([
            Codepoint::fromInt(1),
            Codepoint::fromInt(2),
            Codepoint::fromInt(3),
            Codepoint::fromInt(33)
        ]);

        $this->toRegexCharacterClass()
            ->shouldEqual('[\x{1}\x{2}\x{3}\x{21}]');
    }

    private function givenTheCollectionContains(array $codepoints)
    {
        $this->beConstructedWith(new \ArrayIterator($codepoints));
    }
}