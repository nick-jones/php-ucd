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
     * @return self
     */
    public static function fromHex($value)
    {
        return self::fromInt(hexdec($value));
    }

    /**
     * @param int $value
     * @return self
     */
    public static function fromInt($value)
    {
        return new self($value);
    }

    /**
     * @param string $value
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromUTF8($value)
    {
        $transformationFormat = TransformationFormat::ofType(TransformationFormat::EIGHT);

        return self::fromEncodedCharacter($value, $transformationFormat);
    }

    /**
     * @param string $value
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromUTF16($value)
    {
        $transformationFormat = TransformationFormat::ofType(TransformationFormat::SIXTEEN);

        return self::fromEncodedCharacter($value, $transformationFormat);
    }

    /**
     * @param string $character
     * @param TransformationFormat $encoding
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromEncodedCharacter($character, TransformationFormat $encoding)
    {
        $convertTo = TransformationFormat::ofType(TransformationFormat::THIRTY_TWO_BIG_ENDIAN);
        $converted = TransformationFormat\StringUtility::convertCharacter($character, $encoding, $convertTo);

        return self::fromUTF32($converted);
    }

    /**
     * @param string $value
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromUTF32($value)
    {
        if (strlen($value) !== 4) {
            throw new InvalidArgumentException('Single UTF-32 encoded character must be provided');
        }

        $unpacked = unpack('N', $value);

        return self::fromInt(
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