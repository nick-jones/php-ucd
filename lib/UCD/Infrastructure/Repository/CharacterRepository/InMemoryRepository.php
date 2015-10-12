<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Codepoint;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Character\WritableRepository;
use UCD\Entity\CodepointAssigned;
use UCD\Entity\Character\Repository;

class InMemoryRepository implements WritableRepository
{
    use Repository\Capability\Notify;

    /**
     * @var array
     */
    private $characters = [];

    /**
     * @param Codepoint $codepoint
     * @throws CharacterNotFoundException
     * @return CodepointAssigned
     */
    public function getByCodepoint(Codepoint $codepoint)
    {
        $index = $this->indexFromCodepoint($codepoint);

        if (array_key_exists($index, $this->characters) === false) {
            throw CharacterNotFoundException::withCodepoint($codepoint);
        }

        return $this->characters[$index];
    }

    /**
     * @param CodepointAssigned[] $characters
     * @return bool
     */
    public function addMany($characters)
    {
        foreach ($characters as $character) {
            $codepoint = $character->getCodepoint();
            $index = $this->indexFromCodepoint($codepoint);
            $this->characters[$index] = $character;
            $this->notify();
        }
    }

    /**
     * @return CodepointAssigned[]
     */
    public function getAll()
    {
        return $this->characters;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->characters);
    }

    /**
     * @param Codepoint $codepoint
     * @return int
     */
    private function indexFromCodepoint(Codepoint $codepoint)
    {
        return $codepoint->getValue();
    }
}