<?php

namespace spec\UCD\Entity\Codepoint;

use PhpSpec\ObjectBehavior;

use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Range;
use UCD\Entity\Codepoint\RegexBuilder;

/**
 * @mixin RegexBuilder
 */
class RegexBuilderSpec extends ObjectBehavior
{
    public function it_can_build_a_character_class_from_codepoints()
    {
        $this->addCodepoint(Codepoint::fromInt(1));
        $this->addCodepoint(Codepoint::fromInt(10));

        $this->getCharacterClass()
            ->shouldEqual('[\x{1}\x{A}]');
    }

    public function it_can_build_a_character_class_from_ranges()
    {
        $this->addRange(Range::between(Codepoint::fromInt(1), Codepoint::fromInt(10)));
        $this->addRange(Range::between(Codepoint::fromInt(33), Codepoint::fromInt(33)));

        $this->getCharacterClass()
            ->shouldEqual('[\x{1}-\x{A}\x{21}]');
    }
}