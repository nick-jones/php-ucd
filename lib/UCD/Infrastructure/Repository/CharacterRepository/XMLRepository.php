<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Codepoint;
use UCD\Entity\Character\ReadOnlyRepository;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\CodepointAssigned;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementReader;

class XMLRepository implements ReadOnlyRepository
{
    /**
     * @var ElementReader
     */
    private $elementReader;

    /**
     * @var ElementParser
     */
    private $elementParser;

    /**
     * @param ElementReader $elementReader
     * @param ElementParser $elementParser
     */
    public function __construct(ElementReader $elementReader, ElementParser $elementParser)
    {
        $this->elementReader = $elementReader;
        $this->elementParser = $elementParser;
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
        foreach ($this->elementReader->read() as $element) {
            $characters = $this->filterForCharacters(
                $this->elementParser->parseElement($element)
            );

            foreach ($characters as $character) {
                $codepoint = $character->getCodepoint();
                yield $codepoint->getValue() => $character;
            }
        }
    }

    /**
     * @param object[] $objects
     * @return CodepointAssigned[]
     */
    private function filterForCharacters($objects)
    {
        foreach ($objects as $object) {
            if ($object instanceof CodepointAssigned) {
                $codepoint = $object->getCodepoint();
                yield $codepoint->getValue() => $object;
            }
        }
    }

    /**
     * @return int
     */
    public function count()
    {
        return iterator_count($this->getAll());
    }
}