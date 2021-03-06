<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;
use Prophecy\Argument;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Character\Properties\General\Script;
use UCD\Unicode\Character\Repository\BlockNotFoundException;
use UCD\Unicode\Character\Repository\GeneralCategoryNotFoundException;
use UCD\Unicode\Character\Repository\ScriptNotFoundException;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Character\Properties;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Character\WritableRepository;
use UCD\Infrastructure\Repository\CharacterRepository\NULLRepository;

/**
 * @mixin NULLRepository
 */
class NULLRepositorySpec extends ObjectBehavior
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
        $this->addMany(Character\Collection::fromArray([
            $character->getWrappedObject()
        ]));
    }

    public function it_should_always_throw_CharacterNotFoundException_when_looking_up_a_character(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTheRepositoryHasCharacters([$character]);

        $this->shouldThrow(CharacterNotFoundException::class)
            ->duringGetByCodePoint(Codepoint::fromInt(1));
    }

    public function it_should_always_return_no_results_when_lookup_by_multiple_characters(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTheRepositoryHasCharacters([$character]);

        $this->getByCodepoints(Codepoint\Collection::fromArray([Codepoint::fromInt(1)]))
            ->shouldIterateLike([]);
    }

    public function it_should_always_exposes_nothing_when_retrieving_all_characters(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTheRepositoryHasCharacters([$character]);

        $this->getAll()
            ->shouldIterateLike([]);
    }

    public function it_should_always_return_zero_count(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTheRepositoryHasCharacters([$character]);

        $this->shouldHaveCount(0);
    }

    public function it_should_always_throw_a_BlockNotFoundException_when_searching_by_block()
    {
        $this->shouldThrow(BlockNotFoundException::class)
            ->during('getCodepointsByBlock', [Block::fromValue(Block::ALCHEMICAL_SYMBOLS)]);
    }
    
    public function it_should_always_throw_a_CategoryNotFoundException_when_searching_by_category()
    {
        $this->shouldThrow(GeneralCategoryNotFoundException::class)
            ->during('getCodepointsByCategory', [GeneralCategory::fromValue(GeneralCategory::SYMBOL_MATH)]);
    }

    public function it_should_always_throw_a_ScriptNotFoundException_when_searching_by_script()
    {
        $this->shouldThrow(ScriptNotFoundException::class)
            ->during('getCodepointsByScript', [Script::fromValue(Script::COMMON)]);
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