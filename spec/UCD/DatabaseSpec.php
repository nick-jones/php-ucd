<?php

namespace spec\UCD;

use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;
use Prophecy\Argument;

use UCD\Database;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Collection;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Repository;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Codepoint;
use UCD\Unicode\CodepointAssigned;
use UCD\Unicode\NonCharacter;
use UCD\Unicode\Surrogate;

/**
 * @mixin Database
 */
class DatabaseSpec extends ObjectBehavior
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

    public function it_can_locate_a_codepoint_assigned_entity_by_codepoint(CodepointAssigned $entity)
    {
        $this->repository
            ->getByCodepoint(Codepoint::fromInt(1))
            ->willReturn($entity);

        $this->getByCodepoint(Codepoint::fromInt(1))
            ->shouldReturn($entity);
    }

    public function it_throws_CharacterNotFoundException_if_no_entity_is_assigned_to_the_requested_codepoint()
    {
        $this->repository
            ->getByCodepoint(Argument::any())
            ->willThrow(CharacterNotFoundException::class);

        $this->shouldThrow(CharacterNotFoundException::class)
            ->duringGetByCodepoint(Codepoint::fromInt(1));
    }

    public function it_can_locate_a_character_by_codepoint(Character $character)
    {
        $this->repository
            ->getByCodepoint(Codepoint::fromInt(1))
            ->willReturn($character);

        $this->getCharacterByCodepoint(Codepoint::fromInt(1))
            ->shouldReturn($character);
    }

    public function it_throws_CharacterNotFoundException_if_no_character_is_assigned_to_the_requested_codepoint()
    {
        $this->repository
            ->getByCodepoint(Argument::any())
            ->willThrow(CharacterNotFoundException::class);

        $this->shouldThrow(CharacterNotFoundException::class)
            ->duringGetCharacterByCodepoint(Codepoint::fromInt(1));
    }

    public function it_throws_CharacterNotFoundException_if_something_other_than_a_character_is_assigned_to_a_codepoint(
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

    public function it_provides_the_database_size()
    {
        $this->repository
            ->count()
            ->willReturn(1);

        $this->getSize()
            ->shouldReturn(1);
    }

    public function it_can_provide_all_codepoints_residing_in_a_specific_block()
    {
        $ranges = Codepoint\Range\Collection::fromArray([]);
        $block = Block::fromValue(Block::AEGEAN_NUMBERS);

        $this->repository
            ->getCodepointsByBlock($block)
            ->willReturn($ranges);

        $this->getCodepointsByBlock($block)
            ->shouldReturn($ranges);
    }

    private function givenTheRepositoryContains(array $items)
    {
        $unwrapped = array_map(function (Collaborator $c) {
            return $c->getWrappedObject();
        }, $items);

        $this->repository
            ->getAll()
            ->willReturn(Collection::fromArray($unwrapped));
    }
}