<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;

interface ElementParser
{
    /**
     * @param \DOMElement $element
     * @return mixed
     */
    public function parseElement(\DOMElement $element);
}