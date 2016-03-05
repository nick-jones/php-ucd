<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\KeyGenerator;

use UCD\Unicode\Codepoint\AggregatorRelay\KeyGenerator;
use UCD\Unicode\CodepointAssigned;

class ScriptKeyGenerator implements KeyGenerator
{
    /**
     * @param CodepointAssigned $entity
     * @return string
     */
    public function generateFor(CodepointAssigned $entity)
    {
        $properties = $entity->getGeneralProperties();

        return (string)$properties->getScript();
    }
}