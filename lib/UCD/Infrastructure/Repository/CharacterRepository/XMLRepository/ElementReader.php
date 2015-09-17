<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;

interface ElementReader
{
    /**
     * @return \DOMElement[]
     */
    public function read();
}