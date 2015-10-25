<?php

namespace spec\UCD;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use UCD\Collection;
use UCD\Consumer\Consumer;

use UCD\Entity\Character;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Repository;
use UCD\Entity\CodepointAssigned;
use UCD\Entity\NonCharacter;
use UCD\Entity\Surrogate;

/**
 * @mixin Collection
 */
class CollectionSpec extends ObjectBehavior
{
    /**
     * @var Repository
     */
    private $repository;

    public function let(Repository $repository)
    {
        $this->repository = $repository;

        $this->beConstructedWith($repository);
    }

    public function it_should_be_traversable()
    {
        $this->shouldHaveType(\Traversable::class);
    }

    public function it_can_locate_a_codepoint_assigned_entity_by_codepoint_value(CodepointAssigned $entity)
    {
        $this->repository
            ->getByCodepoint(Codepoint::fromInt(1))
            ->willReturn($entity);

        $this->getByCodepoint(Codepoint::fromInt(1))
            ->shouldReturn($entity);
    }

    public function it_throws_CharacterNotFoundException_if_no_entity_is_assigned_to_the_requested_codepoint_value()
    {
        $this->repository
            ->getByCodepoint(Argument::any())
            ->willThrow(CharacterNotFoundException::class);

        $this->shouldThrow(CharacterNotFoundException::class)
            ->duringGetByCodepoint(Codepoint::fromInt(1));
    }

    public function it_can_locate_a_character_by_codepoint_value(Character $character)
    {
        $this->repository
            ->getByCodepoint(Codepoint::fromInt(1))
            ->willReturn($character);

        $this->getCharacterByCodepoint(Codepoint::fromInt(1))
            ->shouldReturn($character);
    }

    public function it_throws_CharacterNotFoundException_if_no_character_is_assigned_to_the_requested_codepoint_value()
    {
        $this->repository
            ->getByCodepoint(Argument::any())
            ->willThrow(CharacterNotFoundException::class);

        $this->shouldThrow(CharacterNotFoundException::class)
            ->duringGetCharacterByCodepoint(Codepoint::fromInt(1));
    }

    public function it_throws_CharacterNotFoundException_if_something_other_than_a_character_is_assigned_to_a_codepoint_value(
        Surrogate $surrogate
    ) {
        $this->repository
            ->getByCodepoint(Argument::any())
            ->willReturn($surrogate);

        $this->shouldThrow(CharacterNotFoundException::class)
            ->duringGetCharacterByCodepoint(Codepoint::fromInt(1));
    }

    public function it_can_filter_for_characters(CodepointAssigned $c1, Character $c2)
    {
        $this->givenTheRepositoryContains([$c1, $c2]);

        $this->onlyCharacters()
            ->shouldIterateLike([$c2]);
    }

    public function it_can_filter_for_non_characters(CodepointAssigned $c1, NonCharacter $c2)
    {
        $this->givenTheRepositoryContains([$c1, $c2]);

        $this->onlyNonCharacters()
            ->shouldIterateLike([$c2]);
    }

    public function it_can_filter_for_surrogates(CodepointAssigned $c1, Surrogate $c2)
    {
        $this->givenTheRepositoryContains([$c1, $c2]);

        $this->onlySurrogates()
            ->shouldIterateLike([$c2]);
    }

    public function it_can_be_filtered_using_custom_filter_rules(CodepointAssigned $c1, CodepointAssigned $c2)
    {
        $filter = function () {
            static $i = 0;
            return $i++ === 0;
        };

        $this->givenTheRepositoryContains([$c1, $c2]);

        $this->filterWith($filter)
            ->shouldIterateLike([$c1]);
    }

    public function it_can_be_traversed_by_providing_a_callback(
        CodepointAssigned $character
    ) {
        // TODO: use a prediction on an invokable class once phpspec __invoke fix is tagged.

        $this->givenTheRepositoryContains([$character]);
        $count = 0;

        $callback = function (CodepointAssigned $c) use (&$count) {
            ++$count;
        };

        $this->traverseWith($callback);

        if ($count !== 1) {
            throw new \RuntimeException();
        }
    }

    public function it_can_be_traversed_by_providing_a_consumer(
        Consumer $consumer,
        CodepointAssigned $character
    ) {
        $this->givenTheRepositoryContains([$character]);

        $this->traverseWithConsumer($consumer);

        $consumer->consume($character)
            ->shouldHaveBeenCalled();
    }

    private function givenTheRepositoryContains(array $items)
    {
        $this->repository
            ->getAll()
            ->willReturn($items);
    }
}