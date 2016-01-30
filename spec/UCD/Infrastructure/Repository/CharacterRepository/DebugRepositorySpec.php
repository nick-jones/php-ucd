<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use Prophecy\Argument;

use Psr\Log\LoggerInterface;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Character\Repository;
use UCD\Infrastructure\Repository\CharacterRepository\DebugRepository;

/**
 * @mixin DebugRepository
 */
class DebugRepositorySpec extends RepositoryBehaviour
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

    public function it_logs_getByCodepoints_calls($logger)
    {
        $logger->info(Argument::containingString('Repository::getByCodepoints/'))
            ->shouldBeCalled();

        $this->getByCodepoints(Codepoint\Collection::fromArray([Codepoint::fromInt(1)]));
    }

    public function it_delegates_getByCodepoints_calls($repository, Character $character)
    {
        $codepoints = Codepoint\Collection::fromArray([Codepoint::fromInt(1)]);

        $repository->getByCodepoints($codepoints)
            ->willReturn($character);

        $this->getByCodepoints($codepoints)
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

    public function it_logs_getCodepointsByBlock_calls($logger)
    {
        $logger->info(Argument::containingString('Repository::getCodepointsByBlock/'))
            ->shouldBeCalled();

        $this->getCodepointsByBlock(Block::fromValue(Block::AEGEAN_NUMBERS));
    }

    public function it_delegates_getCodepointsByBlock_calls($repository, Character $character)
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