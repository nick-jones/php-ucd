<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties;

use PhpSpec\ObjectBehavior;

use UCD\Entity\Character\Properties\Numericity\NonNumeric;
use UCD\Entity\Character\Properties\Numericity\Numeric;
use UCD\Entity\Character\Properties\Numericity\NumericType;
use UCD\Entity\Character\Properties\Numericity\RationalNumber;
use UCD\Entity\Codepoint;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\NumericityParser;

/**
 * @mixin NumericityParser
 */
class NumericityParserSpec extends ObjectBehavior
{
    const XML_NON_NUMERIC_DATA = <<<XML
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
      <char nt="None" nv="NaN"/>
   </repertoire>
</ucd>
XML;

    const XML_NUMERIC_DATA = <<<XML
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
      <char nt="Nu" nv="1/2"/>
   </repertoire>
</ucd>
XML;

    public function it_parses_numericity_for_non_numeric_characters()
    {
        $type = new NumericType(NumericType::NONE);
        $expected = new NonNumeric($type);

        $element = $this->givenTheCharElementFrom(self::XML_NON_NUMERIC_DATA);

        $this->parseElement($element, Codepoint::fromInt(1))
            ->shouldBeLike($expected);
    }

    public function it_parses_numericity_for_numeric_characters()
    {
        $type = new NumericType(NumericType::NUMERIC);
        $number = new RationalNumber(1, 2, false);
        $expected = new Numeric($type, $number);

        $element = $this->givenTheCharElementFrom(self::XML_NUMERIC_DATA);

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