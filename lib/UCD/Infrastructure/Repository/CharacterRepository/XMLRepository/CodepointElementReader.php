<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;

interface CodepointElementReader
{
    /**
     * @return \DOMElement[]
     */
    public function read();
}