<?php

namespace UCD\Unicode;

use UCD\Exception\InvalidArgumentException;
use UCD\Exception\UnexpectedValueException;

final class TransformationFormat implements Comparable
{
    const EIGHT = '8NE';
    const SIXTEEN = '16NE';
    const SIXTEEN_BIG_ENDIAN = '16BE';
    const SIXTEEN_LITTLE_ENDIAN = '16LE';
    const THIRTY_TWO = '32NE';
    const THIRTY_TWO_BIG_ENDIAN = '32BE';
    const THIRTY_TWO_LITTLE_ENDIAN = '32LE';

    /**
     * @var string[]
     */
    private static $valid = [
        self::EIGHT,
        self::SIXTEEN,
        self::SIXTEEN_BIG_ENDIAN,
        self::SIXTEEN_LITTLE_ENDIAN,
        self::THIRTY_TWO,
        self::THIRTY_TWO_BIG_ENDIAN,
        self::THIRTY_TWO_LITTLE_ENDIAN
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
        if (!in_array($type, self::$valid)) {
            throw new InvalidArgumentException();
        }

        $this->type = $type;
    }

    /**
     * @param string $type
     * @return TransformationFormat
     */
    public static function ofType($type)
    {
        return new self($type);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $other
     * @return bool
     */
    public function equals($other)
    {
        return $other instanceof self
            && $other->type === $this->type;
    }
}