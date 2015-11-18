<?php

namespace UCD\Unicode;

interface CodepointAssigned
{
    /**
     * @return Codepoint
     */
    public function getCodepoint();
}