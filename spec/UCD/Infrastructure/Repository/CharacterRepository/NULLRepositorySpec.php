<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use UCD\Entity\Character;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Properties;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Character\WritableRepository;
use UCD\Infrastructure\Repository\CharacterRepository\NULLRepository;

/**
 * @mixin NULLRepository
 */
class NULLRepositorySpec extends ObjectBehavior
{
    public function it_is_writable()
    {
        $this->shouldHaveType(WritableRepository::class);
    }

    public function it_can_have_characters_added_to_it(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->addMany([$character]);
    }

    public function it_notifies_observers_when_characters_are_added(\SplObserver $observer, Character $character)
    {
        $observer->update($this)
            ->shouldBeCalled();

        $this->attach($observer);
        $this->addMany([$character]);
    }

    public function it_should_always_throw_CharacterNotFoundException_when_looking_up_a_character(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->addMany([$character]);

        $this->shouldThrow(CharacterNotFoundException::class)
            ->duringGetByCodePoint(Codepoint::fromInt(1));
    }

    public function it_should_always_exposes_nothing_when_retrieving_all_characters(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->addMany([$character]);

        $this->getAll()
            ->shouldIterateLike([]);
    }

    public function it_should_always_return_zero_count(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->addMany([$character]);

        $this->shouldHaveCount(0);
    }

    private function givenCharacterHasCodepointWithValue(Character $character, $value)
    {
        $character->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));

        $character->getCodepointValue()
            ->willReturn($value);
    }
}