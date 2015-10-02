<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Codepoint;
use UCD\Entity\Character\Repository;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\CodepointAssigned;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CharacterParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\AggregateParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CodepointCountParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementReader;

class XMLRepository implements Repository
{
    /**
     * @var ElementReader
     */
    private $characterReader;

    /**
     * @var ElementParser
     */
    private $characterParser;

    /**
     * @var CodepointCountParser
     */
    private $codepointParser;

    /**
     * @param ElementReader $characterReader
     * @param ElementParser $characterParser
     * @param CodepointCountParser $codepointParser
     */
    public function __construct(
        ElementReader $characterReader,
        ElementParser $characterParser,
        CodepointCountParser $codepointParser
    ) {
        $this->characterReader = $characterReader;
        $this->characterParser = $characterParser;
        $this->codepointParser = $codepointParser;
    }

    /**
     * @param Codepoint $codepoint
     * @throws CharacterNotFoundException
     * @return CodepointAssigned
     */
    public function getByCodepoint(Codepoint $codepoint)
    {
        foreach ($this->getAll() as $character) {
            if ($codepoint->equals($character->getCodepoint())) {
                return $character;
            }
        }

        throw CharacterNotFoundException::withCodepoint($codepoint);
    }

    /**
     * @return CodepointAssigned[]|\Traversable
     */
    public function getAll()
    {
        foreach ($this->characterReader->read() as $element) {
            foreach ($this->parseCharacters($element) as $character) {
                $codepoint = $character->getCodepoint();
                yield $codepoint->getValue() => $character;
            }
        }
    }

    /**
     * @param \DOMElement $element
     * @return CodepointAssigned[]
     */
    private function parseCharacters(\DOMElement $element)
    {
        return $this->characterParser->parseElement($element);
    }

    /**
     * @return int
     */
    public function count()
    {
        $tally = 0;

        foreach ($this->characterReader->read() as $element) {
            $tally += $this->codepointParser->parseElement($element);
        }

        return $tally;
    }
}