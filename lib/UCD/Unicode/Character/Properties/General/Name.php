<?php

namespace UCD\Unicode\Character\Properties\General;

interface Name
{
    /**
     * @return string|null
     */
    public function getValue();

    /**
     * @return string
     */
    public function __toString();
}