<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use PhpSpec\ObjectBehavior;
use UCD\Entity\CodepointAssigned;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\AggregateParser;

/**
 * @mixin AggregateParser
 */
class AggregateParserSpec extends ObjectBehavior
{
    public function it_delegates_to_the_parser_appropriate_for_the_supplied_element(
        ElementParser $parser,
        CodepointAssigned $parsed
    ) {
        $element = new \DOMElement('char');
        (new \DOMDocument())->appendChild($element);

        $parser->parseElement($element)
            ->willReturn([$parsed]);

        $this->beConstructedWith(['char' => $parser]);

        $this->parseElement($element)
            ->shouldReturn([$parsed]);
    }
}