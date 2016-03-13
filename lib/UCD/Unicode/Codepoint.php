<?php

namespace UCD\Unicode;

use UCD\Exception\InvalidArgumentException;
use UCD\Exception\OutOfRangeException;
use UCD\Unicode\TransformationFormat\StringUtility;

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
        return self::fromEncodedCharacter(
            $value,
            TransformationFormat::ofType(TransformationFormat::EIGHT)
        );
    }

    /**
     * @param string $value
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromUTF16LE($value)
    {
        return self::fromEncodedCharacter(
            $value,
            TransformationFormat::ofType(TransformationFormat::SIXTEEN_LITTLE_ENDIAN)
        );
    }

    /**
     * @param string $value
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromUTF16BE($value)
    {
        return self::fromEncodedCharacter(
            $value,
            TransformationFormat::ofType(TransformationFormat::SIXTEEN_BIG_ENDIAN)
        );
    }

    /**
     * @param string $value
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromUTF32LE($value)
    {
        return self::fromEncodedCharacter(
            $value,
            TransformationFormat::ofType(TransformationFormat::THIRTY_TWO_LITTLE_ENDIAN)
        );
    }

    /**
     * @param string $value
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromUTF32BE($value)
    {
        return self::fromEncodedCharacter(
            $value,
            TransformationFormat::ofType(TransformationFormat::THIRTY_TWO_BIG_ENDIAN)
        );
    }

    /**
     * @param string $character
     * @param TransformationFormat $convertFrom
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromEncodedCharacter($character, TransformationFormat $convertFrom)
    {
        $convertTo = TransformationFormat::ofType(TransformationFormat::THIRTY_TWO_BIG_ENDIAN);
        $character = StringUtility::convertCharacter($character, $convertFrom, $convertTo);
        $unpacked = unpack('N', $character);

        return self::fromInt(
            array_shift($unpacked)
        );
    }

    /**
     * @return string
     */
    public function toUTF8()
    {
        return $this->toEncodedCharacter(
            TransformationFormat::ofType(TransformationFormat::EIGHT)
        );
    }

    /**
     * @return string
     */
    public function toUTF16LE()
    {
        return $this->toEncodedCharacter(
            TransformationFormat::ofType(TransformationFormat::SIXTEEN_LITTLE_ENDIAN)
        );
    }

    /**
     * @return string
     */
    public function toUTF16BE()
    {
        return $this->toEncodedCharacter(
            TransformationFormat::ofType(TransformationFormat::SIXTEEN_BIG_ENDIAN)
        );
    }

    /**
     * @return string
     */
    public function toUTF32LE()
    {
        return $this->toEncodedCharacter(
            TransformationFormat::ofType(TransformationFormat::THIRTY_TWO_LITTLE_ENDIAN)
        );
    }

    /**
     * @return string
     */
    public function toUTF32BE()
    {
        return $this->toEncodedCharacter(
            TransformationFormat::ofType(TransformationFormat::THIRTY_TWO_BIG_ENDIAN)
        );
    }

    /**
     * @param TransformationFormat $convertTo
     * @return string
     * @throws InvalidArgumentException
     */
    public function toEncodedCharacter(TransformationFormat $convertTo)
    {
        $character = pack('N', $this->value);
        $convertFrom = TransformationFormat::ofType(TransformationFormat::THIRTY_TWO_BIG_ENDIAN);

        return StringUtility::convertCharacter($character, $convertFrom, $convertTo);
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