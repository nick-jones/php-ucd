<?php

namespace UCD\Entity\Character\Properties\General\Name;

use UCD\Entity\Character\Properties\General\Name;

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