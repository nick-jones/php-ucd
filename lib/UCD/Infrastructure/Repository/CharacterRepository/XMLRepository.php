<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Unicode\Character\Collection;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Character\Repository;
use UCD\Unicode\CodepointAssigned;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CodepointAssignedParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CodepointCountParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\CodepointElementReader;

class XMLRepository implements Repository
{
    use Repository\Capability\CodepointSearchByIteration;
    use Repository\Capability\PropertySearchByIteration;

    /**
     * @var CodepointElementReader
     */
    private $elementReader;

    /**
     * @var CodepointAssignedParser
     */
    private $elementParser;

    /**
     * @var CodepointCountParser
     */
    private $countParser;

    /**
     * @param CodepointElementReader $characterReader
     * @param CodepointAssignedParser $characterParser
     * @param CodepointCountParser $codepointParser
     */
    public function __construct(
        CodepointElementReader $characterReader,
        CodepointAssignedParser $characterParser,
        CodepointCountParser $codepointParser
    ) {
        $this->elementReader = $characterReader;
        $this->elementParser = $characterParser;
        $this->countParser = $codepointParser;
    }

    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        return new Collection(
            $this->readAll()
        );
    }

    /**
     * @return \Generator
     */
    private function readAll()
    {
        foreach ($this->elementReader->read() as $element) {
            $characters = $this->parseElementForCharacters($element);

            foreach ($characters as $character) {
                $codepoint = $character->getCodepoint();
                yield $codepoint->getValue() => $character;
            }
        }
    }

    /**
     * @param \DOMElement $element
     * @return CodepointAssigned[]
     */
    private function parseElementForCharacters(\DOMElement $element)
    {
        return $this->elementParser->parseElement($element);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        $tally = 0;

        foreach ($this->elementReader->read() as $element) {
            $tally += $this->parseElementForCharacterCount($element);
        }

        return $tally;
    }

    /**
     * @param \DOMElement $element
     * @return int
     */
    private function parseElementForCharacterCount(\DOMElement $element)
    {
        return $this->countParser->parseElement($element);
    }
}