<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Character;
use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Character\WritableRepository;

class InMemoryRepository implements WritableRepository
{
    /**
     * @var array
     */
    private $characters = [];

    /**
     * @param Codepoint $codepoint
     * @throws CharacterNotFoundException
     * @return Character
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
     * @param Character[] $characters
     * @return bool
     */
    public function addMany($characters)
    {
        foreach ($characters as $character) {
            $codepoint = $character->getCodepoint();
            $index = $this->indexFromCodepoint($codepoint);
            $this->characters[$index] = $character;
        }
    }

    /**
     * @return Character[]
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