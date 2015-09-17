<?php

namespace UCD\Entity\Character\Properties\Name;

use UCD\Entity\Character\Properties\Name;

class Alias extends Assigned
{
    /**
     * @var AliasType
     */
    private $type;

    /**
     * @param string $value
     * @param AliasType $type
     */
    public function __construct($value, AliasType $type)
    {
        $this->type = $type;

        parent::__construct($value);
    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }
}