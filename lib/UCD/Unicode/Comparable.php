<?php

namespace UCD\Unicode;

interface Comparable
{
    /**
     * @param mixed $other
     * @return bool
     */
    public function equals($other);
}