<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Psr\Log\LoggerInterface;

use UCD\Entity\Character;
use UCD\Entity\Character\WritableRepository;
use UCD\Infrastructure\Repository\CharacterRepository\DebugWritableRepository;

/**
 * @mixin DebugWritableRepository
 */
class DebugWritableRepositorySpec extends ObjectBehavior
{
    public function let(WritableRepository $repository, LoggerInterface $logger)
    {
        $this->beConstructedWith($repository, $logger);
    }

    public function it_logs_every_added_character($logger, Character $c1, Character $c2)
    {
        $logger->info(Argument::containingString('Repository::addMany/'))
            ->shouldBeCalled();

        $this->addMany([$c1, $c2]);
    }

    public function it_delegates_addMany_calls($repository, Character $character)
    {
        $characters = [$character];

        $repository->addMany($characters)
            ->shouldBeCalled();

        $this->addMany($characters);
    }
}