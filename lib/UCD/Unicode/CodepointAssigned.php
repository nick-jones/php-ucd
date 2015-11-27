<?php

namespace UCD\Unicode;

use UCD\Unicode\Character\Properties\General;

interface CodepointAssigned
{
    /**
     * @return Codepoint
     */
    public function getCodepoint();

    /**
     * @return General
     */
    public function getGeneralProperties();
}