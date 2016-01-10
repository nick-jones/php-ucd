<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use UCD\Exception\InvalidArgumentException;

class Property
{
    const BLOCK = 'block';

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     * @throws InvalidArgumentException
     */
    private function __construct($type)
    {
        if ($type !== self::BLOCK) {
            throw new InvalidArgumentException();
        }

        $this->type = $type;
    }

    /**
     * @param string $type
     * @return Property
     */
    public static function ofType($type)
    {
        return new self($type);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->type;
    }
}
