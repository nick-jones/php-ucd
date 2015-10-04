<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use UCD\Entity\Codepoint;
use UCD\Entity\NonCharacter;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\GeneralParser;

class NonCharacterParser implements CodepointAwareParser
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
     * @return mixed
     */
    public function parseElement(\DOMElement $element, Codepoint $codepoint = null)
    {
        $general = $this->generalParser->parseElement($element, $codepoint);

        return new NonCharacter($codepoint, $general);
    }
}