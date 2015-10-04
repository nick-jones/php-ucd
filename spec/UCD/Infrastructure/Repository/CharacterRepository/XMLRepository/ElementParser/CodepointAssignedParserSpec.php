<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use PhpSpec\ObjectBehavior;

use UCD\Entity\Character;
use UCD\Entity\Codepoint;
use UCD\Entity\NonCharacter;
use UCD\Entity\Surrogate;

use UCD\Exception\RuntimeException;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CharacterParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CodepointAssignedParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\NonCharacterParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\SurrogateParser;

/**
 * @mixin CodepointAssignedParser
 */
class CodepointAssignedParserSpec extends ObjectBehavior
{
    public function let(
        CharacterParser $characterParser,
        NonCharacterParser $nonCharacterParser,
        SurrogateParser $surrogateParser
    ) {
        $this->beConstructedWith($characterParser, $nonCharacterParser, $surrogateParser);
    }

    public function it_delegates_to_the_character_parser_for_character_elements($characterParser, Character $character)
    {
        $element = $this->givenAnElementWithTagName(CodepointAssignedParser::TAG_NAME_CHAR);
        $element->setAttribute('cp', 1);

        $characterParser->parseElement($element, Codepoint::fromInt(1))
            ->willReturn($character);

        $this->parseElement($element)
            ->shouldIterateLike([$character]);
    }

    public function it_delegates_to_the_non_character_parser_for_non_character_elements(
        $nonCharacterParser, NonCharacter $nonCharacter
    ) {
        $element = $this->givenAnElementWithTagName(CodepointAssignedParser::TAG_NAME_NON_CHAR);
        $element->setAttribute('cp', 1);

        $nonCharacterParser->parseElement($element, Codepoint::fromInt(1))
            ->willReturn($nonCharacter);

        $this->parseElement($element)
            ->shouldIterateLike([$nonCharacter]);
    }

    public function it_delegates_to_the_surrogate_parser_for_surrogate_elements(
        $surrogateParser, Surrogate $surrogate
    ) {
        $element = $this->givenAnElementWithTagName(CodepointAssignedParser::TAG_NAME_SURROGATE);
        $element->setAttribute('cp', 1);

        $surrogateParser->parseElement($element, Codepoint::fromInt(1))
            ->willReturn($surrogate);

        $this->parseElement($element)
            ->shouldIterateLike([$surrogate]);
    }

    public function it_delegates_for_each_codepoint_in_a_range($characterParser, Character $c1, Character $c2)
    {
        $element = $this->givenAnElementWithTagName(CodepointAssignedParser::TAG_NAME_CHAR);
        $element->setAttribute('first-cp', 1);
        $element->setAttribute('last-cp', 2);

        $characterParser->parseElement($element, Codepoint::fromInt(1))
            ->willReturn($c1);

        $characterParser->parseElement($element, Codepoint::fromInt(2))
            ->willReturn($c2);

        $this->parseElement($element)
            ->shouldIterateLike([$c1, $c2]);
    }

    public function it_throws_RuntimeException_if_the_element_holds_an_unknown_tag()
    {
        $element = $this->givenAnElementWithTagName('foobar');

        $this->parseElement($element)
            ->shouldThrow(RuntimeException::class)
            ->duringNext();
    }

    /**
     * @param string $tagName
     * @return \DOMElement
     */
    public function givenAnElementWithTagName($tagName)
    {
        $element = new \DOMElement($tagName);
        (new \DOMDocument())->appendChild($element);

        return $element;
    }
}