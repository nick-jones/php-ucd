<?php

namespace spec\UCD\Entity\Character;

use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;

use UCD\Consumer\Consumer;
use UCD\Entity\Character\Collection;
use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;
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

    public function it_can_be_filtered_using_custom_filter_rules(CodepointAssigned $c1, CodepointAssigned $c2)
    {
        $filter = function (CodepointAssigned $c) {
            static $i = 0;
            return $i++ === 0;
        };

        $this->givenTheCollectionContains([$c1, $c2]);

        $this->filterWith($filter)
            ->shouldIterateLike([$c1]);
    }

    public function it_can_be_traversed_by_providing_a_callback(CodepointAssigned $character)
    {
        // TODO: use a prediction on an invokable class once phpspec __invoke fix is tagged.
        $this->givenTheCollectionContains([$character]);
        $count = 0;
        $callback = function (CodepointAssigned $c) use (&$count) { ++$count; };

        $this->traverseWith($callback);

        if ($count !== 1) {
            throw new \RuntimeException();
        }
    }

    public function it_can_be_traversed_by_providing_a_consumer(Consumer $consumer, CodepointAssigned $character)
    {
        $this->givenTheCollectionContains([$character]);
        $this->traverseWithConsumer($consumer);

        $consumer->consume($character)
            ->shouldHaveBeenCalled();
    }

    public function it_can_be_reduced_to_codepoints(CodepointAssigned $character1, CodepointAssigned $character2)
    {
        $this->givenEntityHasCodepointWithValue($character1, 1);
        $this->givenEntityHasCodepointWithValue($character2, 10);
        $this->givenTheCollectionContains([$character1, $character2]);

        $this->asCodepoints()
            ->shouldIterateLike([Codepoint::fromInt(1), Codepoint::fromInt(10)]);
    }

    public function it_can_be_reduced_to_codepoint_ranges(
        CodepointAssigned $character1,
        CodepointAssigned $character2,
        CodepointAssigned $character3
    ) {
        $this->givenEntityHasCodepointWithValue($character1, 1);
        $this->givenEntityHasCodepointWithValue($character2, 2);
        $this->givenEntityHasCodepointWithValue($character3, 33);
        $this->givenTheCollectionContains([$character1, $character2, $character3]);

        $this->asCodepointRanges()
            ->shouldIterateLike([
                Codepoint\Range::between(Codepoint::fromInt(1), Codepoint::fromInt(2)),
                Codepoint\Range::between(Codepoint::fromInt(33), Codepoint::fromInt(33)),
            ]);
    }

    public function it_can_be_reduced_to_a_regular_expression_character_class(
        CodepointAssigned $character1,
        CodepointAssigned $character2,
        CodepointAssigned $character3
    ) {
        $this->givenEntityHasCodepointWithValue($character1, 1);
        $this->givenEntityHasCodepointWithValue($character2, 2);
        $this->givenEntityHasCodepointWithValue($character3, 33);
        $this->givenTheCollectionContains([$character1, $character2, $character3]);

        $this->asRegexCharacterClass()
            ->shouldEqual('[\x{1}-\x{2}\x{21}]');
    }

    public function it_can_be_transformed_to_an_array(CodepointAssigned $character)
    {
        $this->givenTheCollectionContains([$character]);

        $this->toArray()
            ->shouldReturn([$character]);
    }

    private function givenTheCollectionContains(array $items)
    {
        $unwrapped = array_map(function (Collaborator $c) {
            return $c->getWrappedObject();
        }, $items);

        $this->beConstructedWith(new \ArrayIterator($unwrapped));
    }

    private function givenEntityHasCodepointWithValue(CodepointAssigned $entity, $value)
    {
        $entity->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));
    }
}