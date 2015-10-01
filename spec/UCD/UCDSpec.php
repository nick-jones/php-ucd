<?php

namespace spec\UCD;

use PhpSpec\ObjectBehavior;
use UCD\Entity\Character;
use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\ReadOnlyRepository;
use UCD\UCD;

/**
 * @mixin UCD
 */
class UCDSpec extends ObjectBehavior
{
    public function let(ReadOnlyRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    public function it_can_locate_a_character_in_the_UCD_by_codepoint_value($repository, Character $character)
    {
        $repository->getByCodepoint(Codepoint::fromInt(1))
            ->willReturn($character);

        $this->locate(1)
            ->shouldReturn($character);
    }

    public function it_exposes_all_characters_in_the_UCD($repository, Character $character)
    {
        $repository->getAll()
            ->willReturn([$character]);

        $this->all()
            ->shouldReturn([$character]);
    }

    public function it_can_filter_characters_in_the_UCD_using_a_user_supplied_callback(
        $repository,
        Character $character1,
        Character $character2
    ) {
        $repository->getAll()
            ->willReturn([$character1, $character2]);

        $filter = function () {
            static $i = 0;
            return $i++ === 0;
        };

        $this->filter($filter)
            ->shouldIterateLike([$character1]);
    }

    public function it_can_walk_all_characters_in_the_UCD_using_a_user_supplied_callback(
        $repository,
        Character $character1,
        Character $character2
    ) {
        $repository->getAll()
            ->willReturn([$character1, $character2]);

        $hits = 0;

        $callback = function (Character $character) use (&$hits) {
            $hits++;
        };

        $this->walk($callback)
            ->shouldReturn(true);

        if ($hits !== 2) {
            throw new \UnexpectedValueException();
        }
    }
}