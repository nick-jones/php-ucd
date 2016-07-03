<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties;

use PhpSpec\ObjectBehavior;

use UCD\Unicode\Character\Properties\LetterCase;
use UCD\Unicode\Character\Properties\General;
use UCD\Unicode\Codepoint;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\LetterCaseParser;

/**
 * @mixin LetterCaseParser
 */
class LetterCaseParserSpec extends ObjectBehavior
{
    const XML_DATA = <<<XML
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
      <char lc="0065" slc="0065" tc="#" stc="#" suc="#" uc="#" cf="#" scf="#"/>
   </repertoire>
</ucd>
XML;

    public function it_parses_case_properties()
    {
        $codepoint = Codepoint::fromInt(0);
        $codepoints = Codepoint\Collection::fromArray([$codepoint]);
        $lower = Codepoint::fromHex('65');
        $lowers = Codepoint\Collection::fromArray([$lower]);
        $mappings = new LetterCase\Mappings(
            new LetterCase\Mapping($lower, $lowers),
            new LetterCase\Mapping($codepoint, $codepoints),
            new LetterCase\Mapping($codepoint, $codepoints),
            new LetterCase\Mapping($codepoint, $codepoints)
        );
        $expected = new LetterCase($mappings);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML(self::XML_DATA);
        $element = $dom->getElementsByTagName('char')->item(0);

        $this->parseElement($element, $codepoint)
            ->shouldBeLike($expected);
    }
}