<?php

namespace UCD\Consumer;

use UCD\Entity\CodepointAssigned;

interface Consumer
{
    /**
     * @param CodepointAssigned $entity
     */
    public function consume(CodepointAssigned $entity);
}