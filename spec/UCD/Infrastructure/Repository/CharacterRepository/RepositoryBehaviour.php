<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use PhpSpec\ObjectBehavior;
use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties\General;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Codepoint;

class RepositoryBehaviour extends ObjectBehavior
{
    protected function givenCharacterHasCodepointWithValue(Character $character, $value)
    {
        $character->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));

        $character->getCodepointValue()
            ->willReturn($value);
    }

    protected function givenCharacterResidesInBlock(Character $character, Block $block)
    {
        $general = new General(
            new General\Names(new General\Name\Unassigned()),
            $block,
            new General\Version(General\Version::UNKNOWN),
            new General\GeneralCategory(General\GeneralCategory::LETTER_TITLECASE)
        );

        $character->getGeneralProperties()
            ->willReturn($general);
    }
}