<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use UCD\Unicode\Codepoint;
use UCD\Unicode\CodepointAssigned;
use UCD\Exception\RuntimeException;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

class CodepointAssignedParser implements ElementParser
{
    const TAG_NAME_CHAR = 'char';
    const TAG_NAME_NON_CHAR = 'noncharacter';
    const TAG_NAME_SURROGATE = 'surrogate';

    const ATTR_CODEPOINT = 'cp';
    const ATTR_CODEPOINT_FIRST = 'first-cp';
    const ATTR_CODEPOINT_LAST = 'last-cp';

    /**
     * @var CharacterParser
     */
    private $characterParser;

    /**
     * @var NonCharacterParser
     */
    private $nonCharacterParser;

    /**
     * @var SurrogateParser
     */
    private $surrogateParser;

    /**
     * @param CharacterParser $characterParser
     * @param NonCharacterParser $nonCharacterParser
     * @param SurrogateParser $surrogateParser
     */
    public function __construct(
        CharacterParser $characterParser,
        NonCharacterParser $nonCharacterParser,
        SurrogateParser $surrogateParser
    ) {
        $this->characterParser = $characterParser;
        $this->nonCharacterParser = $nonCharacterParser;
        $this->surrogateParser = $surrogateParser;
    }

    /**
     * @param \DOMElement $element
     * @return CodepointAssigned[]
     */
    public function parseElement(\DOMElement $element)
    {
        $parser = $this->getParserForElement($element);
        $codepointValues = $this->extractCodepoints($element);

        foreach ($codepointValues as $codepointValue) {
            $codepoint = Codepoint::fromInt($codepointValue);
            yield $parser->parseElement($element, $codepoint);
        }
    }

    /**
     * @param \DOMElement $element
     * @return CodepointAwareParser
     * @throws RuntimeException
     */
    private function getParserForElement(\DOMElement $element)
    {
        if ($element->tagName === self::TAG_NAME_CHAR) {
            return $this->characterParser;
        } elseif ($element->tagName === self::TAG_NAME_NON_CHAR) {
            return $this->nonCharacterParser;
        } elseif ($element->tagName === self::TAG_NAME_SURROGATE) {
            return $this->surrogateParser;
        }

        throw new RuntimeException(sprintf('No parser found for %s', $element->tagName));
    }

    /**
     * @param \DOMElement $element
     * @return \int[]
     */
    private function extractCodepoints(\DOMElement $element)
    {
        if ($element->hasAttribute(self::ATTR_CODEPOINT)) {
            $first = hexdec($element->getAttribute(self::ATTR_CODEPOINT));
            $last = $first;
        } else {
            $first = hexdec($element->getAttribute(self::ATTR_CODEPOINT_FIRST));
            $last = hexdec($element->getAttribute(self::ATTR_CODEPOINT_LAST));
        }

        return range($first, $last);
    }
}