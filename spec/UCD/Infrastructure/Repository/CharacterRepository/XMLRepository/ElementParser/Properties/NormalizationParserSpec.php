<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties;

use PhpSpec\ObjectBehavior;

use UCD\Entity\Character\Properties\Normalization;
use UCD\Entity\Character\Properties\Normalization\Combining;
use UCD\Entity\Character\Properties\Normalization\DecompositionType;
use UCD\Entity\Codepoint;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\NormalizationParser;

/**
 * @mixin NormalizationParser
 */
class NormalizationParserSpec extends ObjectBehavior
{
    const XML_DATA = <<<XML
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
      <char ccc="1" dt="sup" dm="0061" />
   </repertoire>
</ucd>
XML;

    public function it_parses_normalization_scope_properties()
    {
        $combining = new Combining(Combining::OVERLAY);
        $decompositionType = new DecompositionType(DecompositionType::SUP);
        $decomposition = new Normalization\Decomposition\Assigned($decompositionType, [Codepoint::fromInt(97)]);
        $expected = new Normalization($combining, $decomposition);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML(self::XML_DATA);
        $element = $dom->getElementsByTagName('char')->item(0);

        $this->parseElement($element, Codepoint::fromInt(0))
            ->shouldBeLike($expected);
    }
}