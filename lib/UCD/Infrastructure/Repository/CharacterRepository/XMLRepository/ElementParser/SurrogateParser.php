<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use UCD\Unicode\Codepoint;
use UCD\Unicode\Surrogate;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\GeneralParser;

class SurrogateParser implements CodepointAwareParser
{
    /**
     * @var GeneralParser
     */
    private $generalParser;

    /**
     * @param GeneralParser $generalParser
     */
    public function __construct(GeneralParser $generalParser)
    {
        $this->generalParser = $generalParser;
    }

    /**
     * @param \DOMElement $element
     * @param Codepoint $codepoint
     * @return Surrogate
     */
    public function parseElement(\DOMElement $element, Codepoint $codepoint)
    {
        $general = $this->generalParser->parseElement($element, $codepoint);

        return new Surrogate($codepoint, $general);
    }
}