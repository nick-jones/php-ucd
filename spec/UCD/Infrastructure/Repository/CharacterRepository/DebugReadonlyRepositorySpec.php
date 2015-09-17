<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Psr\Log\LoggerInterface;

use UCD\Entity\Character;
use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\ReadOnlyRepository;
use UCD\Infrastructure\Repository\CharacterRepository\DebugReadonlyRepository;

/**
 * @mixin DebugReadonlyRepository
 */
class DebugReadonlyRepositorySpec extends ObjectBehavior
{
    public function let(ReadOnlyRepository $repository, LoggerInterface $logger)
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