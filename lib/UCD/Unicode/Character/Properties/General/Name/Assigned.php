<?php

namespace UCD\Unicode\Character\Properties\General\Name;

use UCD\Unicode\Character\Properties\General\Name;
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
     * {@inheritDoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return $this->getValue();
    }
}