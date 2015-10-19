<?php

namespace UCD\Traverser;

use UCD\Entity\CodepointAssigned;

abstract class Traverser
{
    /**
     * @param CodepointAssigned $entity
     */
    abstract protected function consume(CodepointAssigned $entity);

    /**
     * @param CodepointAssigned $entity
     */
    public function __invoke(CodepointAssigned $entity)
    {
        $this->consume($entity);
    }
}