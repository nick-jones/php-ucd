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

        $this->addMany(Character\Collection::fromArray([
            $c1->getWrappedObject(),
            $c2->getWrappedObject()
        ]));
    }

    public function it_delegates_addMany_calls($repository, Character $character)
    {
        $characters = Character\Collection::fromArray([
            $character->getWrappedObject()
        ]);

        $repository->addMany($characters)
            ->shouldBeCalled();

        $this->addMany($characters);
    }

    public function it_notifies_observers_when_characters_are_added(\SplObserver $observer, Character $character)
    {
        $observer->update($this)
            ->shouldBeCalled();

        $this->attach($observer);
        $this->addMany(Character\Collection::fromArray([
            $character->getWrappedObject()
        ]));
    }
}