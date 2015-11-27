<?php

namespace UCD\Unicode\AggregatorRelay;

use UCD\Unicode\CodepointAssigned;

interface KeyGenerator
{
    /**
     * @param CodepointAssigned $entity
     * @return string
     */
    public function generateFor(CodepointAssigned $entity);
}