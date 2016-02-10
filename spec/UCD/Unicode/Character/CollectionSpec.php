<?php

namespace spec\UCD\Unicode\Character;

use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Collection;
use UCD\Unicode\Codepoint;
use UCD\Unicode\CodepointAssigned;
use UCD\Unicode\Collection as CollectionInterface;
use UCD\Unicode\NonCharacter;
use UCD\Unicode\Surrogate;

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

    public function it_can_be_reduced_to_codepoints(CodepointAssigned $character1, CodepointAssigned $character2)
    {
        $this->givenEntityHasCodepointWithValue($character1, 1);
        $this->givenEntityHasCodepointWithValue($character2, 10);
        $this->givenTheCollectionContains([$character1, $character2]);

        $this->extractCodepoints()
            ->shouldIterateLike([Codepoint::fromInt(1), Codepoint::fromInt(10)]);
    }

    public function it_can_be_transformed_to_an_array(CodepointAssigned $character)
    {
        $this->givenTheCollectionContains([$character]);

        $this->toArray()
            ->shouldReturn([$character]);
    }

    public function it_can_be_filtered_to_just_characters(Character $character, Surrogate $surrogate)
    {
        $this->givenTheCollectionContains([$character, $surrogate]);

        $this->getCharacters()
            ->shouldIterateLike([$character]);
    }

    public function it_can_be_filtered_to_just_non_characters(NonCharacter $nonCharacter, Character $character)
    {
        $this->givenTheCollectionContains([$nonCharacter, $character]);

        $this->getNonCharacters()
            ->shouldIterateLike([$nonCharacter]);
    }

    public function it_can_be_filtered_to_just_surrogates(Surrogate $surrogate, Character $character)
    {
        $this->givenTheCollectionContains([$surrogate, $character]);

        $this->getNonCharacters()
            ->shouldIterateLike([$surrogate]);
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