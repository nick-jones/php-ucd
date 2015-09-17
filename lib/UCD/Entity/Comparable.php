<?php

namespace UCD\Entity;

interface Comparable
{
    /**
     * @param mixed $other
     * @return bool
     */
    public function equals($other);
}