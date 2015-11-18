<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use UCD\Unicode\Codepoint;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

interface CodepointAwareParser
{
    /**
     * @param \DOMElement $element
     * @param Codepoint $codepoint
     * @return mixed
     */
    public function parseElement(\DOMElement $element, Codepoint $codepoint);
}