<?php

namespace spec\UCD\Unicode\Codepoint;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use UCD\Unicode\Codepoint;
use UCD\Unicode\Codepoint\Collection;
use UCD\Unicode\Collection as CollectionInterface;

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

    public function it_can_indicate_whether_or_not_a_codepoint_is_represented()
    {
        $this->givenTheCollectionContains([Codepoint::fromInt(1), Codepoint::fromInt(2)]);

        $this->has(Codepoint::fromInt(1))
            ->shouldReturn(true);
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

    public function it_can_be_constructed_from_a_UTF8_encoded_string()
    {
        $this->beConstructedThrough('fromUTF8', ['ab']);

        $this->shouldIterateLike([
            Codepoint::fromInt(97),
            Codepoint::fromInt(98)
        ]);
    }

    public function it_can_be_constructed_from_a_UTF16BE_encoded_string()
    {
        $this->beConstructedThrough('fromUTF16BE', ["\x00\x61\x00\x62"]);

        $this->shouldIterateLike([
            Codepoint::fromInt(97),
            Codepoint::fromInt(98)
        ]);
    }

    public function it_can_be_constructed_from_a_UTF16LE_encoded_string()
    {
        $this->beConstructedThrough('fromUTF16LE', ["\x61\x00\x62\x00"]);

        $this->shouldIterateLike([
            Codepoint::fromInt(97),
            Codepoint::fromInt(98)
        ]);
    }

    public function it_can_be_constructed_from_a_UTF32BE_encoded_string()
    {
        $this->beConstructedThrough('fromUTF32BE', ["\x00\x00\x00\x61\x00\x00\x00\x62"]);

        $this->shouldIterateLike([
            Codepoint::fromInt(97),
            Codepoint::fromInt(98)
        ]);
    }

    public function it_can_be_constructed_from_a_UTF32LE_encoded_string()
    {
        $this->beConstructedThrough('fromUTF32LE', ["\x61\x00\x00\x00\x62\x00\x00\x00"]);

        $this->shouldIterateLike([
            Codepoint::fromInt(97),
            Codepoint::fromInt(98)
        ]);
    }

    public function it_can_provide_a_UTF8_representation_of_its_values()
    {
        $this->givenTheCollectionContains([Codepoint::fromInt(0x1F377), Codepoint::fromInt(0x61)]);

        $this->toUTF8()
            ->shouldReturn("\xF0\x9F\x8D\xB7\x61");
    }

    public function it_can_provide_a_UTF16BE_representation_of_its_values()
    {
        $this->givenTheCollectionContains([Codepoint::fromInt(0x1F377), Codepoint::fromInt(0x61)]);

        $this->toUTF16BE()
            ->shouldReturn("\xD8\x3C\xDF\x77\x00\x61");
    }

    public function it_can_provide_a_UTF16LE_representation_of_its_values()
    {
        $this->givenTheCollectionContains([Codepoint::fromInt(0x1F377), Codepoint::fromInt(0x61)]);

        $this->toUTF16LE()
            ->shouldReturn("\x3C\xD8\x77\xDF\x61\x00");
    }

    public function it_can_provide_a_UTF32BE_representation_of_its_values()
    {
        $this->givenTheCollectionContains([Codepoint::fromInt(0x1F377), Codepoint::fromInt(0x61)]);

        $this->toUTF32BE()
            ->shouldReturn("\x00\x01\xF3\x77\x00\x00\x00\x61");
    }

    public function it_can_provide_a_UTF32LE_representation_of_its_values()
    {
        $this->givenTheCollectionContains([Codepoint::fromInt(0x1F377), Codepoint::fromInt(0x61)]);

        $this->toUTF32LE()
            ->shouldReturn("\x77\xF3\x01\x00\x61\x00\x00\x00");
    }

    private function givenTheCollectionContains(array $codepoints)
    {
        $this->beConstructedWith(new \ArrayIterator($codepoints));
    }
}