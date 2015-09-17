<?php

namespace UCD\Entity\Character\Properties\Name;

use UCD\Entity\Character\Properties\Name;

class Unassigned implements Name
{
    /**
     * @return string
     */
    public function getValue()
    {
        return null;
    }
}