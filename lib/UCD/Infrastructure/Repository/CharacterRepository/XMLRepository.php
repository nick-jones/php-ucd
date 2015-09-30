<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\ReadOnlyRepository;
use UCD\Entity\Character;
use UCD\Entity\Character\Repository\CharacterNotFoundException;

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
     * @return Character
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
     * @return Character[]
     */
    public function getAll()
    {
        foreach ($this->elementReader->read() as $element) {
            $characters = $this->filterForCharacters(
                $this->elementParser->parseElement($element)
            );

            foreach ($characters as $character) {
                yield $character->getCodepointValue() => $character;
            }
        }
    }

    /**
     * @param object[] $objects
     * @return Character[]
     */
    private function filterForCharacters($objects)
    {
        foreach ($objects as $object) {
            if ($object instanceof Character) {
                yield $object->getCodepointValue() => $object;
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