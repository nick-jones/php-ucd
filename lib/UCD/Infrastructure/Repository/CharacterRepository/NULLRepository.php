<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Collection as CharacterCollection;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Character\WritableRepository;
use UCD\Unicode\Character\Repository;

class NULLRepository implements WritableRepository
{
    use Repository\Capability\Notify;
    use Repository\Capability\BlockSearchByIteration;

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
    public function getByCodepoints(Codepoint\Collection $codepoints)
    {
        return Character\Collection::fromArray([]);
    }

    /**
     * {@inheritDoc}
     */
    public function addMany(CharacterCollection $characters)
    {
        $this->notify();
    }

    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        return CharacterCollection::fromArray([]);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return 0;
    }
}