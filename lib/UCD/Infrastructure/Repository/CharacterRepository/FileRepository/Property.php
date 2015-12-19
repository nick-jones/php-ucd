<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use UCD\Exception\InvalidArgumentException;

class Property
{
    const PROPERTY_BLOCK = 'block';

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     * @throws InvalidArgumentException
     */
    private function __construct($name)
    {
        if ($name !== self::PROPERTY_BLOCK) {
            throw new InvalidArgumentException();
        }

        $this->name = $name;
    }

    /**
     * @param string $name
     * @return Property
     */
    public static function withName($name)
    {
        return new self($name);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
