<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;

interface ElementParser
{
    /**
     * @param \DOMElement $element
     * @return object[]
     */
    public function parseElement(\DOMElement $element);
}