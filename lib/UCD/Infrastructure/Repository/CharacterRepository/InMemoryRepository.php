<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Collection;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Character\WritableRepository;
use UCD\Unicode\Character\Repository;
use UCD\Unicode\CodepointAssigned;

class InMemoryRepository implements WritableRepository
{
    use Repository\Capability\Notify;
    use Repository\Capability\PropertySearchByIteration;

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
    public function getByCodepoints(Codepoint\Collection $codepoints)
    {
        $results = [];

        foreach ($codepoints as $codepoint) {
            try {
                $character = $this->getByCodepoint($codepoint);
                array_push($results, $character);
            } catch (CharacterNotFoundException $e) { }
        }

        return Character\Collection::fromArray($results);
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