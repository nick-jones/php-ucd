<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties;

use PhpSpec\ObjectBehavior;

use UCD\Unicode\Character\Properties\General;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Character\Properties\General\Name\Assigned;
use UCD\Unicode\Character\Properties\General\Name\Unassigned;
use UCD\Unicode\Character\Properties\General\Version;
use UCD\Unicode\Codepoint;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\GeneralParser;

/**
 * @mixin GeneralParser
 */
class GeneralParserSpec extends ObjectBehavior
{
    const XML_DATA = <<<XML
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
      <char age="1.1" na="" na1="NULL" blk="ASCII" gc="Cc"/>
   </repertoire>
</ucd>
XML;

    public function it_parses_general_scope_properties()
    {
        $primary = new Unassigned();
        $version1 = new Assigned('NULL');
        $names = new General\Names($primary, [], $version1);
        $block = new Block(Block::BASIC_LATIN);
        $age = new Version(Version::V1_1);
        $generalCategory = new GeneralCategory(GeneralCategory::OTHER_CONTROL);
        $expected = new General($names, $block, $age, $generalCategory);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML(self::XML_DATA);
        $element = $dom->getElementsByTagName('char')->item(0);

        $this->parseElement($element, Codepoint::fromInt(0))
            ->shouldBeLike($expected);
    }
}