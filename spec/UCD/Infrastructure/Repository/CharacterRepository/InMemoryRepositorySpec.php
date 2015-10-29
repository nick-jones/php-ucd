<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;
use Prophecy\Argument;

use UCD\Entity\Character;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Properties;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Character\WritableRepository;
use UCD\Infrastructure\Repository\CharacterRepository\InMemoryRepository;

/**
 * @mixin InMemoryRepository
 */
class InMemoryRepositorySpec extends ObjectBehavior
{
    public function it_is_writable()
    {
        $this->shouldHaveType(WritableRepository::class);
    }

    public function it_can_have_characters_added_to_it(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->addMany(Character\Collection::fromArray([
            $character->getWrappedObject()
        ]));
    }

    public function it_notifies_observers_when_characters_are_added(\SplObserver $observer, Character $character)
    {
        $observer->update($this)
            ->shouldBeCalled();

        $this->attach($observer);
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->addMany(Character\Collection::fromArray([
            $character->getWrappedObject()
        ]));
    }

    public function it_can_retrieve_characters_by_codepoint(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTheRepositoryHasCharacters([$character]);

        $this->getByCodepoint(Codepoint::fromInt(1))
            ->shouldReturn($character);
    }

    public function it_should_throw_CharacterNotFoundException_if_the_requested_character_is_not_found()
    {
        $this->shouldThrow(CharacterNotFoundException::class)
            ->duringGetByCodePoint(Codepoint::fromInt(1));
    }

    public function it_exposes_all_available_characters(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTheRepositoryHasCharacters([$character]);

        $this->getAll()
            ->shouldIterateLike([1 => $character]);
    }

    public function it_exposes_an_nothing_if_no_characters_are_available()
    {
        $this->getAll()
            ->shouldIterateLike([]);
    }

    public function it_exposes_the_number_of_characters_available(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTheRepositoryHasCharacters([$character]);

        $this->shouldHaveCount(1);
    }

    private function givenCharacterHasCodepointWithValue(Character $character, $value)
    {
        $character->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));

        $character->getCodepointValue()
            ->willReturn($value);
    }

    private function givenTheRepositoryHasCharacters(array $items)
    {
        $unwrapped = array_map(function (Collaborator $item) {
            return $item->getWrappedObject();
        }, $items);

        $this->addMany(Character\Collection::fromArray($unwrapped));
    }
}