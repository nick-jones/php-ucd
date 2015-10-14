<?php

namespace UCD\Traverser;

use UCD\Entity\CodepointAssigned;

abstract class Traverser
{
    /**
     * @param CodepointAssigned $entity
     */
    protected abstract function consume(CodepointAssigned $entity);

    /**
     * @param CodepointAssigned $entity
     */
    public function __invoke(CodepointAssigned $entity)
    {
        $this->consume($entity);
    }
}