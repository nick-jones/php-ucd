<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties;

use PhpSpec\ObjectBehavior;

use UCD\Entity\Character\Properties\Bidirectionality;
use UCD\Entity\Character\Properties\Bidirectionality\Bracket;
use UCD\Entity\Character\Properties\Bidirectionality\BracketBidirectionality;
use UCD\Entity\Character\Properties\Bidirectionality\Classing;
use UCD\Entity\Character\Properties\Bidirectionality\Mirroring;
use UCD\Entity\Codepoint;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\BidirectionalityParser;

/**
 * @mixin BidirectionalityParser
 */
class BidirectionalityParserSpec extends ObjectBehavior
{
    const XML_BIDI_DATA = <<<XML
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
      <char bc="BN" bpt="n" bpb="#" Bidi_M="N" bmg="" Bidi_C="N"/>
   </repertoire>
</ucd>
XML;

    const XML_BIDI_BRACKET_DATA = <<<XML
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
      <char bc="ON" bpt="o" bpb="0029" Bidi_M="Y" bmg="0029" Bidi_C="N"/>
   </repertoire>
</ucd>
XML;

    public function it_parses_bidirectionality_for_non_bracket_characters()
    {
        $classing = new Classing(Classing::BOUNDARY_NEUTRAL);
        $mirroring = new Mirroring(false);
        $expected = new Bidirectionality($classing, $mirroring, false);

        $element = $this->givenTheCharElementFrom(self::XML_BIDI_DATA);

        $this->parseElement($element, Codepoint::fromInt(1))
            ->shouldBeLike($expected);
    }

    public function it_parses_bidirectionality_for_bracket_characters()
    {
        $classing = new Classing(Classing::OTHER_NEUTRAL);
        $mirroring = new Mirroring(true, Codepoint::fromInt(41));
        $bracket = Bracket::createOpen(Codepoint::fromInt(41));
        $expected = new BracketBidirectionality($classing, $mirroring, false, $bracket);

        $element = $this->givenTheCharElementFrom(self::XML_BIDI_BRACKET_DATA);

        $this->parseElement($element, Codepoint::fromInt(1))
            ->shouldBeLike($expected);
    }

    /**
     * @param string $xml
     * @return \DOMElement
     */
    private function givenTheCharElementFrom($xml)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML($xml);

        return $dom->getElementsByTagName('char')->item(0);
    }
}