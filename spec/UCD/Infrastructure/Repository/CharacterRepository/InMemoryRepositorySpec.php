<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use PhpSpec\Wrapper\Collaborator;
use Prophecy\Argument;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties\General;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Character\Properties;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Character\WritableRepository;
use UCD\Infrastructure\Repository\CharacterRepository\InMemoryRepository;
use UCD\Unicode\Codepoint\Range;

/**
 * @mixin InMemoryRepository
 */
class InMemoryRepositorySpec extends RepositoryBehaviour
{
    public function it_is_writable()
    {
        $this->shouldImplement(WritableRepository::class);
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

    public function it_exposes_codepoints_for_a_requested_block(Character $c1, Character $c2, Character $c3)
    {
        $this->givenCharacterHasCodepointWithValue($c1, 1);
        $this->givenCharacterHasCodepointWithValue($c2, 2);
        $this->givenCharacterHasCodepointWithValue($c3, 3);

        $this->givenCharacterResidesInBlock($c1, Block::fromValue(Block::AEGEAN_NUMBERS));
        $this->givenCharacterResidesInBlock($c2, Block::fromValue(Block::AEGEAN_NUMBERS));
        $this->givenCharacterResidesInBlock($c3, Block::fromValue(Block::AHOM));

        $this->givenTheRepositoryHasCharacters([$c1, $c2, $c3]);

        $this->getCodepointsByBlock(Block::fromValue(Block::AEGEAN_NUMBERS))
            ->shouldIterateLike([Range::between(Codepoint::fromInt(1), Codepoint::fromInt(2))]);
    }

    public function it_exposes_the_number_of_characters_available(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTheRepositoryHasCharacters([$character]);

        $this->shouldHaveCount(1);
    }

    private function givenTheRepositoryHasCharacters(array $items)
    {
        $unwrapped = array_map(function (Collaborator $item) {
            return $item->getWrappedObject();
        }, $items);

        $this->addMany(Character\Collection::fromArray($unwrapped));
    }
}