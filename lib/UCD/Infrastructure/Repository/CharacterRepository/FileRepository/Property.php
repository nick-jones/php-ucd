<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use UCD\Exception\InvalidArgumentException;

class Property
{
    const BLOCK = 'block';
    const GENERAL_CATEGORY = 'gc';

    /**
     * @var bool[]
     */
    private static $valid = [
        self::BLOCK => true,
        self::GENERAL_CATEGORY => true
    ];

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
        if (!$this->isKnownType($type)) {
            throw new InvalidArgumentException();
        }

        $this->type = $type;
    }

    /**
     * @param string $type
     * @return bool
     */
    private function isKnownType($type)
    {
        return array_key_exists($type, self::$valid)
            && self::$valid[$type] === true;
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
