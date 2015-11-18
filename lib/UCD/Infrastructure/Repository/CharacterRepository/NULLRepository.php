<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Unicode\Character\Collection;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Character\WritableRepository;
use UCD\Unicode\Character\Repository;

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