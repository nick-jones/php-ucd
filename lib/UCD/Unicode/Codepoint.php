<?php

namespace UCD\Unicode;

use UCD\Exception\InvalidArgumentException;
use UCD\Exception\OutOfRangeException;

class Codepoint implements Comparable
{
    const MIN = 0x0;
    const MAX = 0x10FFFF;

    /**
     * @var int
     */
    private $value;

    /**
     * @param int $value
     * @throws OutOfRangeException
     * @throws InvalidArgumentException
     */
    private function __construct($value)
    {
        if (!is_int($value)) {
            throw new InvalidArgumentException('Codepoint value must be an integer');
        }

        if ($value < self::MIN || $value > self::MAX) {
            throw new OutOfRangeException('Codepoint value must reside between 0x0 and 0x10FFFF');
        }

        $this->value = $value;
    }

    /**
     * @param string $value
     * @return Codepoint
     */
    public static function fromHex($value)
    {
        return self::fromInt(hexdec($value));
    }

    /**
     * @param int $value
     * @return Codepoint
     */
    public static function fromInt($value)
    {
        return new self($value);
    }

    /**
     * @param string $value
     * @return Codepoint
     * @throws InvalidArgumentException
     */
    public static function fromUTF8($value)
    {
        if (iconv_strlen($value, 'UTF-8') !== 1) {
            throw new InvalidArgumentException('Single character must be provided');
        }

        $unpacked = unpack('N', iconv('UTF-8', 'UTF-32BE', $value));

        return new self(
            array_shift($unpacked)
        );
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $other
     * @return bool
     */
    public function equals($other)
    {
        if ($this === $other) {
            return true;
        }

        return $other instanceof self
            && $this->value === $other->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('U+%X', $this->value);
    }
}