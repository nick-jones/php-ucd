<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use PhpSpec\ObjectBehavior;
use UCD\Entity\Character;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Codepoint;
use UCD\Infrastructure\Repository\CharacterRepository\TraversableRepository;

/**
 * @mixin TraversableRepository
 */
class TraversableRepositorySpec extends ObjectBehavior
{
    /**
     * @var \ArrayObject
     */
    private $supplier;

    public function let()
    {
        $this->supplier = new \ArrayObject();
        $this->beConstructedWith($this->supplier);
    }

    public function it_can_retrieve_characters_by_codepoint(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTraversableContains($character);

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
        $this->givenTraversableContains($character);

        $this->getAll()
            ->shouldIterateLike([1 => $character]);
    }

    public function it_exposes_an_empty_array_if_no_characters_are_available()
    {
        $this->getAll()
            ->shouldIterateLike([]);
    }

    public function it_exposes_the_number_of_characters_available(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTraversableContains($character);

        $this->shouldHaveCount(1);
    }

    private function givenCharacterHasCodepointWithValue(Character $character, $value)
    {
        $character->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));

        $character->getCodepointValue()
            ->willReturn($value);
    }

    private function givenTraversableContains(Character $character)
    {
        $wrapped = $character->getWrappedObject();
        $this->supplier[$wrapped->getCodepointValue()] = $wrapped;
    }
}