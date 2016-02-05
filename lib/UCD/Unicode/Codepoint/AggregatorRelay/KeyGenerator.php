<?php

namespace UCD\Unicode\Codepoint\AggregatorRelay;

use UCD\Unicode\CodepointAssigned;

interface KeyGenerator
{
    /**
     * @param CodepointAssigned $entity
     * @return string
     */
    public function generateFor(CodepointAssigned $entity);
}