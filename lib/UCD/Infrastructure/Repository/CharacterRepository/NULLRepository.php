<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Character\Collection;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Character\WritableRepository;
use UCD\Entity\Character\Repository;

class NULLRepository implements WritableRepository
{
    use Repository\Capability\Notify;

    /**
     * {@inheritDoc}
     */
    public function getByCodepoint(Codepoint $codepoint)
    {
        throw CharacterNotFoundException::withCodepoint($codepoint);
    }

    /**
     * {@inheritDoc}
     */
    public function addMany(Collection $characters)
    {
        $this->notify();
    }

    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        return Collection::fromArray([]);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return 0;
    }
}