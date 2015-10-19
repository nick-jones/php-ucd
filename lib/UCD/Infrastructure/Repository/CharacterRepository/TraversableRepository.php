<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Character\Repository;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;

class TraversableRepository implements Repository
{
    use Repository\Capability\SearchByIteration;

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
     * {@inheritDoc}
     */
    public function getAll()
    {
        return $this->traversable;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return iterator_count($this->traversable);
    }
}