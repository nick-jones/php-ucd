<?php

namespace spec\UCD\View;

use PhpSpec\ObjectBehavior;
use UCD\Entity\Character;
use UCD\Entity\Character\Properties;
use UCD\View\CharacterView;

/**
 * @mixin CharacterView
 */
class CharacterViewSpec extends ObjectBehavior
{
    public function let(Character $character)
    {
        $this->beConstructedWith($character);
    }

    public function it_can_transform_a_codepoint_to_1_byte_UTF8($character)
    {
        $character->getCodepointValue()
            ->willReturn(97);

        $this->asUTF8()
            ->shouldReturn('a');
    }

    public function it_can_transform_a_codepoint_to_2_byte_UTF8($character)
    {
        $character->getCodepointValue()
            ->willReturn(163);

        $this->asUTF8()
            ->shouldReturn('£');
    }

    public function it_can_transform_a_codepoint_to_3_byte_UTF8($character)
    {
        $character->getCodepointValue()
            ->willReturn(9731);

        $this->asUTF8()
            ->shouldReturn('☃');
    }

    public function it_can_transform_a_codepoint_to_4_byte_UTF8($character)
    {
        $character->getCodepointValue()
            ->willReturn(127828);

        $this->asUTF8()
            ->shouldReturn('🍔');
    }

    public function it_can_dump_an_export()
    {
        $this->asExport()
            ->shouldMatch(sprintf('/%s/', preg_quote(Character::CLASS)));
    }
}