<?php

namespace integration\UCD\CharacterRepository;

use UCD\Entity\Character\Collection;
use UCD\Entity\Codepoint;
use UCD\Infrastructure\Repository\CharacterRepository\InMemoryRepository;

class InMemoryRepositoryTest extends TestCase
{
    protected function setUp()
    {
        $this->repository = new InMemoryRepository();
        $character = $this->buildCharacterWithCodepoint(Codepoint::fromInt(0));
        $characters = Collection::fromArray([$character]);
        $this->repository->addMany($characters);
    }
}