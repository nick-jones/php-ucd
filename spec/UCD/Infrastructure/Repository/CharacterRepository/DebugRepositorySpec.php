<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Psr\Log\LoggerInterface;

use UCD\Entity\Character;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Repository;
use UCD\Infrastructure\Repository\CharacterRepository\DebugRepository;

/**
 * @mixin DebugRepository
 */
class DebugRepositorySpec extends ObjectBehavior
{
    public function let(Repository $repository, LoggerInterface $logger)
    {
        $this->beConstructedWith($repository, $logger);
    }

    public function it_logs_getByCodepoint_calls($logger)
    {
        $logger->info(Argument::containingString('Repository::getByCodepoint/'))
            ->shouldBeCalled();

        $this->getByCodepoint(Codepoint::fromInt(1));
    }

    public function it_delegates_getByCodepoint_calls($repository, Character $character)
    {
        $codepoint = Codepoint::fromInt(1);

        $repository->getByCodepoint($codepoint)
            ->willReturn($character);

        $this->getByCodepoint($codepoint)
            ->shouldReturn($character);
    }

    public function it_logs_getAll_calls($logger)
    {
        $logger->info(Argument::containingString('Repository::getAll/'))
            ->shouldBeCalled();

        $this->getAll();
    }

    public function it_delegates_getAll_calls($repository, Character\Collection $collection)
    {
        $repository->getAll()
            ->willReturn($collection);

        $this->getAll()
            ->shouldReturn($collection);
    }

    public function it_delegates_find_calls($repository, Character $character)
    {
        $repository->getAll()
            ->willReturn([$character]);

        $this->getAll()
            ->shouldReturn([$character]);
    }

    public function it_logs_count_calls($logger)
    {
        $logger->info(Argument::containingString('Repository::count/'))
            ->shouldBeCalled();

        $this->count();
    }

    public function it_delegates_count_calls($repository, Character $character)
    {
        $repository->getAll()
            ->willReturn([$character]);

        $this->getAll()
            ->shouldReturn([$character]);
    }
}