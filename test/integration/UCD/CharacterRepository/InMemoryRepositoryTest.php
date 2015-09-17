<?php

namespace integration\UCD\CharacterRepository;

use UCD\Entity\Character\Codepoint;
use UCD\Infrastructure\Repository\CharacterRepository\InMemoryRepository;

class InMemoryRepositoryTest extends TestCase
{
    protected function setUp()
    {
        $this->repository = new InMemoryRepository();
        $character = $this->buildCharacterWithCodepoint(Codepoint::fromInt(0));
        $this->repository->addMany([$character]);
    }
}