<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use UCD\Exception\RuntimeException;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

class AggregateParser implements ElementParser
{
    /**
     * @var ElementParser[]
     */
    private $delegates;

    /**
     * @param array $delegates
     */
    public function __construct(array $delegates)
    {
        $this->delegates = $delegates;
    }

    /**
     * @param \DOMElement $element
     * @return object[]
     */
    public function parseElement(\DOMElement $element)
    {
        $parser = $this->getParserForParser($element);

        return $parser->parseElement($element);
    }

    /**
     * @param \DOMElement $element
     * @return ElementParser
     * @throws RuntimeException
     */
    private function getParserForParser(\DOMElement $element)
    {
        if (!array_key_exists($element->tagName, $this->delegates)) {
            throw new RuntimeException();
        }

        return $this->delegates[$element->tagName];
    }
}