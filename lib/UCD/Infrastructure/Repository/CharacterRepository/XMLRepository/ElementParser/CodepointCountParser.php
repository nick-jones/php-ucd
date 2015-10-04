<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

class CodepointCountParser implements ElementParser
{
    /**
     * @param \DOMElement $element
     * @return int
     */
    public function parseElement(\DOMElement $element)
    {
        if ($element->hasAttribute(CodepointAssignedParser::ATTR_CODEPOINT)) {
            return 1;
        }

        $first = hexdec($element->getAttribute(CodepointAssignedParser::ATTR_CODEPOINT_FIRST));
        $last = hexdec($element->getAttribute(CodepointAssignedParser::ATTR_CODEPOINT_LAST));

        return $last - $first + 1;
    }
}