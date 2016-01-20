<?php

namespace UCD\Unicode\Codepoint\Aggregator;

use UCD\Unicode\Codepoint\Aggregator;

class Factory
{
    /**
     * @return Aggregator
     */
    public function create()
    {
        return new Aggregator();
    }
}