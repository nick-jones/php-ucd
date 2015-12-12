<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\KeyGenerator;

use UCD\Unicode\AggregatorRelay\KeyGenerator;
use UCD\Unicode\CodepointAssigned;

class BlockKeyGenerator implements KeyGenerator
{
    /**
     * @param CodepointAssigned $entity
     * @return string
     */
    public function generateFor(CodepointAssigned $entity)
    {
        $properties = $entity->getGeneralProperties();

        return (string)$properties->getBlock();
    }
}