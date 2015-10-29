<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Character\Collection;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Character\WritableRepository;
use UCD\Entity\Character\Repository;
use UCD\Entity\CodepointAssigned;

class InMemoryRepository implements WritableRepository
{
    use Repository\Capability\Notify;

    /**
     * @var array
     */
    private $characters = [];

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function addMany(Collection $characters)
    {
        $characters->traverseWith(function (CodepointAssigned $c) {
            $codepoint = $c->getCodepoint();
            $index = $this->indexFromCodepoint($codepoint);
            $this->characters[$index] = $c;
            $this->notify();
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        return Collection::fromArray($this->characters);
    }

    /**
     * {@inheritDoc}
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