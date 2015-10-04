<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties;

use PhpSpec\ObjectBehavior;
use UCD\Entity\Character\Properties\Shaping;
use UCD\Entity\Character\Properties\Shaping\JoiningGroup;
use UCD\Entity\Character\Properties\Shaping\JoiningType;
use UCD\Entity\Codepoint;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\ShapingParser;

/**
 * @mixin ShapingParser
 */
class ShapingParserSpec extends ObjectBehavior
{
    const XML_DATA = <<<XML
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
      <char jt="L" jg="Ain" Join_C="N"/>
   </repertoire>
</ucd>
XML;

    public function it_parses_shaping_scope_properties()
    {
        $group = new JoiningGroup(JoiningGroup::AIN);
        $type = new JoiningType(JoiningType::LEFT_JOINING);
        $joining = new Shaping\Joining($group, $type, false);
        $expected = new Shaping($joining);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML(self::XML_DATA);
        $element = $dom->getElementsByTagName('char')->item(0);

        $this->parseElement($element, Codepoint::fromInt(0))
            ->shouldBeLike($expected);
    }
}