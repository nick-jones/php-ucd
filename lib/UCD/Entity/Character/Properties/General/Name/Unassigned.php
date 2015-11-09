<?php

namespace UCD\Entity\Character\Properties\General\Name;

use UCD\Entity\Character\Properties\General\Name;

class Unassigned implements Name
{
    /**
     * {@inheritDoc}
     */
    public function getValue()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return '';
    }
}