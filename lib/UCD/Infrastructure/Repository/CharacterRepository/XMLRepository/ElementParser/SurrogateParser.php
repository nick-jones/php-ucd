<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;
use UCD\Entity\Surrogate;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

class SurrogateParser extends Base
{
    /**
     * @return CodepointAssigned[]
     */
    protected function parse()
    {
        $codepointValues = $this->extractCodepoints();

        foreach ($codepointValues as $codepointValue) {
            $codepoint = Codepoint::fromInt($codepointValue);
            $properties = $this->parseGeneral($codepoint);
            yield new Surrogate($codepoint, $properties);
        }
    }
}