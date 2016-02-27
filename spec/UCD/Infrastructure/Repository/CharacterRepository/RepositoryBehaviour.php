<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use PhpSpec\ObjectBehavior;
use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties\General;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Codepoint;

class RepositoryBehaviour extends ObjectBehavior
{
    /**
     * @param Character $character
     * @param int $value
     */
    protected function givenCharacterHasCodepointWithValue($character, $value)
    {
        $character->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));

        $character->getCodepointValue()
            ->willReturn($value);
    }

    /**
     * @param Character $character
     * @param Block $block
     */
    protected function givenCharacterResidesInBlock($character, $block)
    {
        $general = new General(
            new General\Names(new General\Name\Unassigned()),
            $block,
            new General\Version(General\Version::UNKNOWN),
            new GeneralCategory(GeneralCategory::LETTER_TITLECASE),
            new General\Script(General\Script::COMMON)
        );

        $character->getGeneralProperties()
            ->willReturn($general);
    }

    /**
     * @param Character $character
     * @param GeneralCategory $category
     */
    protected function givenCharacterResidesInCategory($character, $category)
    {
        $general = new General(
            new General\Names(new General\Name\Unassigned()),
            new Block(Block::CYRILLIC),
            new General\Version(General\Version::UNKNOWN),
            $category,
            new General\Script(General\Script::COMMON)
        );

        $character->getGeneralProperties()
            ->willReturn($general);
    }
}