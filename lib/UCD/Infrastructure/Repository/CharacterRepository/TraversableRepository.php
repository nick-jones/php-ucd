<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Character\Repository;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;

class TraversableRepository implements Repository
{
    /**
     * @var \Traversable|CodepointAssigned[]
     */
    private $traversable;

    /**
     * @param \Traversable $traversable
     */
    public function __construct(\Traversable $traversable)
    {
        $this->traversable = $traversable;
    }

    /**
     * @param Codepoint $codepoint
     * @return CodepointAssigned
     * @throws CharacterNotFoundException
     */
    public function getByCodepoint(Codepoint $codepoint)
    {
        foreach ($this->traversable as $assigned) {
            if ($codepoint->equals($assigned->getCodepoint())) {
                return $assigned;
            }
        }

        throw CharacterNotFoundException::withCodepoint($codepoint);
    }

    /**
     * @return CodepointAssigned[]
     */
    public function getAll()
    {
        return $this->traversable;
    }

    /**
     * @return int
     */
    public function count()
    {
        return iterator_count($this->traversable);
    }
}