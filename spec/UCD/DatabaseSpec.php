<?php

namespace spec\UCD;

use PhpSpec\ObjectBehavior;
use UCD\Database;
use UCD\Entity\Character;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Repository;
use UCD\Entity\CodepointAssigned;

/**
 * @mixin Database
 */
class DatabaseSpec extends ObjectBehavior
{
    public function let(Repository $repository)
    {
        $this->beConstructedWith($repository);
    }

    public function it_can_locate_a_codepoint_assigned_entity_in_the_UCD_by_codepoint_value(
        $repository,
        CodepointAssigned $assigned
    ) {
        $repository->getByCodepoint(Codepoint::fromInt(1))
            ->willReturn($assigned);

        $this->locate(1)
            ->shouldReturn($assigned);
    }

    public function it_can_locate_a_character_in_the_UCD_by_codepoint_value($repository, Character $character)
    {
        $repository->getByCodepoint(Codepoint::fromInt(1))
            ->willReturn($character);

        $this->locateCharacter(1)
            ->shouldReturn($character);
    }

    public function it_throws_if_the_located_assigned_entity_is_not_a_character(
        $repository,
        CodepointAssigned $assigned
    ) {
        $repository->getByCodepoint(Codepoint::fromInt(1))
            ->willReturn($assigned);

        $this->shouldThrow(CharacterNotFoundException::class)
            ->duringLocateCharacter(1);
    }

    public function it_exposes_all_assigned_entities_in_the_UCD($repository, CodepointAssigned $assigned)
    {
        $repository->getAll()
            ->willReturn([$assigned]);

        $this->all()
            ->shouldReturn([$assigned]);
    }

    public function it_exposes_all_assigned_characters_in_the_UCD(
        $repository,
        CodepointAssigned $assigned,
        Character $character
    ) {
        $repository->getAll()
            ->willReturn([$assigned, $character]);

        $this->allCharacters()
            ->shouldIterateLike([$character]);
    }

    public function it_can_filter_entities_in_the_UCD_using_a_user_supplied_callback(
        $repository,
        CodepointAssigned $assigned1,
        CodepointAssigned $assigned2
    ) {
        $repository->getAll()
            ->willReturn([$assigned1, $assigned2]);

        $filter = function () {
            static $i = 0;
            return $i++ === 0;
        };

        $this->filter($filter)
            ->shouldIterateLike([$assigned1]);
    }

    public function it_can_filter_characters_in_the_UCD_using_a_user_supplied_callback(
        $repository,
        CodepointAssigned $assigned,
        Character $character1,
        Character $character2
    ) {
        $repository->getAll()
            ->willReturn([$assigned, $character1, $character2]);

        $filter = function () {
            static $i = 0;
            return $i++ === 0;
        };

        $this->filterCharacters($filter)
            ->shouldIterateLike([$character1]);
    }

    public function it_can_walk_all_entities_in_the_UCD_using_a_user_supplied_callback(
        $repository,
        CodepointAssigned $assigned1,
        CodepointAssigned $assigned2
    ) {
        $repository->getAll()
            ->willReturn([$assigned1, $assigned2]);

        $hits = 0;

        $callback = function (CodepointAssigned $c) use (&$hits) {
            $hits++;
        };

        $this->walk($callback)
            ->shouldReturn(true);

        if ($hits !== 2) {
            throw new \UnexpectedValueException();
        }
    }

    public function it_can_walk_all_characters_in_the_UCD_using_a_user_supplied_callback(
        $repository,
        CodepointAssigned $assigned,
        Character $character1,
        Character $character2
    ) {
        $repository->getAll()
            ->willReturn([$assigned, $character1, $character2]);

        $hits = 0;

        $callback = function (Character $c) use (&$hits) {
            $hits++;
        };

        $this->walkCharacters($callback)
            ->shouldReturn(true);

        if ($hits !== 2) {
            throw new \UnexpectedValueException();
        }
    }
}