<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use PhpSpec\ObjectBehavior;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CodepointCountParser;

/**
 * @mixin CodepointCountParser
 */
class CodepointCountParserSpec extends ObjectBehavior
{
    const XML_DATA_SINGLE_CP = <<<EOX
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
      <char cp="0000"/>
   </repertoire>
</ucd>
EOX;

    const XML_DATA_MULTI_CP = <<<EOX
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
      <char first-cp="0000" last-cp="000A"/>
   </repertoire>
</ucd>
EOX;

    public function it_returns_one_if_the_element_represents_a_single_codepoint()
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML(self::XML_DATA_SINGLE_CP);
        $element = $dom->getElementsByTagName('char')->item(0);

        $this->parseElement($element)
            ->shouldReturn(1);
    }

    public function it_returns_the_range_count_if_the_element_represents_multiple_codepoints()
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML(self::XML_DATA_MULTI_CP);
        $element = $dom->getElementsByTagName('char')->item(0);

        $this->parseElement($element)
            ->shouldReturn(11);
    }
}