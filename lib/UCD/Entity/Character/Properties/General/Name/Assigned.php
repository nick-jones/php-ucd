<?php

namespace UCD\Entity\Character\Properties\General\Name;

use UCD\Entity\Character\Properties\General\Name;
use UCD\Exception\InvalidArgumentException;

class Assigned implements Name
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     * @throws InvalidArgumentException
     */
    public function __construct($value)
    {
        if (!is_string($value) || strlen($value) === 0) {
            throw new InvalidArgumentException('Invalid name');
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}