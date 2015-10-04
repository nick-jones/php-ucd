<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use UCD\Entity\Codepoint;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

interface CodepointAwareParser extends ElementParser
{
    /**
     * @param \DOMElement $element
     * @param Codepoint $codepoint
     * @return mixed
     */
    public function parseElement(\DOMElement $element, Codepoint $codepoint = null);
}