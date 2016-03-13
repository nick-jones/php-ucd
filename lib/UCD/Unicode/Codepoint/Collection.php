<?php

namespace UCD\Unicode\Codepoint;

use UCD\Unicode\Codepoint;
use UCD\Unicode\Collection\TraversableBackedCollection;
use UCD\Unicode\TransformationFormat;
use UCD\Unicode\TransformationFormat\StringUtility;

class Collection extends TraversableBackedCollection
{
    /**
     * @return \Traversable|int[]
     */
    public function flatten()
    {
        /** @var Codepoint $codepoint */
        foreach ($this as $codepoint) {
            yield $codepoint->getValue();
        }
    }

    /**
     * @param Codepoint $codepoint
     * @return bool
     */
    public function has(Codepoint $codepoint)
    {
        foreach ($this as $check) {
            if ($codepoint->equals($check)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Range[]|Range\Collection
     */
    public function aggregate()
    {
        $aggregator = new Aggregator();

        $this->traverseWith(function (Codepoint $codepoint) use ($aggregator) {
            $aggregator->addCodepoint($codepoint);
        });

        return $aggregator->getAggregated();
    }

    /**
     * @return string
     */
    public function toRegexCharacterClass()
    {
        $builder = new RegexBuilder();

        $this->traverseWith(function (Codepoint $codepoint) use ($builder) {
            $builder->addCodepoint($codepoint);
        });

        return $builder->getCharacterClass();
    }

    /**
     * @param string $string
     * @return static
     */
    public static function fromUTF8($string)
    {
        return self::fromEncodedString(
            $string,
            TransformationFormat::ofType(TransformationFormat::EIGHT)
        );
    }

    /**
     * @param string $string
     * @return static
     */
    public static function fromUTF16LE($string)
    {
        return self::fromEncodedString(
            $string,
            TransformationFormat::ofType(TransformationFormat::SIXTEEN_LITTLE_ENDIAN)
        );
    }

    /**
     * @param string $string
     * @return static
     */
    public static function fromUTF16BE($string)
    {
        return self::fromEncodedString(
            $string,
            TransformationFormat::ofType(TransformationFormat::SIXTEEN_BIG_ENDIAN)
        );
    }

    /**
     * @param string $string
     * @return static
     */
    public static function fromUTF32LE($string)
    {
        return self::fromEncodedString(
            $string,
            TransformationFormat::ofType(TransformationFormat::THIRTY_TWO_LITTLE_ENDIAN)
        );
    }

    /**
     * @param string $string
     * @return static
     */
    public static function fromUTF32BE($string)
    {
        return self::fromEncodedString(
            $string,
            TransformationFormat::ofType(TransformationFormat::THIRTY_TWO_BIG_ENDIAN)
        );
    }

    /**
     * @param string $string
     * @param TransformationFormat $encoding
     * @return static
     */
    public static function fromEncodedString($string, TransformationFormat $encoding)
    {
        $characters = StringUtility::split($string, $encoding);

        $mapper = function ($character) use ($encoding) {
            return Codepoint::fromEncodedCharacter($character, $encoding);
        };

        return static::fromArray(
            array_map($mapper, $characters)
        );
    }

    /**
     * @return string
     */
    public function toUTF8()
    {
        return $this->toEncodedString(
            TransformationFormat::ofType(TransformationFormat::EIGHT)
        );
    }

    /**
     * @return string
     */
    public function toUTF16LE()
    {
        return $this->toEncodedString(
            TransformationFormat::ofType(TransformationFormat::SIXTEEN_LITTLE_ENDIAN)
        );
    }

    /**
     * @return string
     */
    public function toUTF16BE()
    {
        return $this->toEncodedString(
            TransformationFormat::ofType(TransformationFormat::SIXTEEN_BIG_ENDIAN)
        );
    }

    /**
     * @return string
     */
    public function toUTF32LE()
    {
        return $this->toEncodedString(
            TransformationFormat::ofType(TransformationFormat::THIRTY_TWO_LITTLE_ENDIAN)
        );
    }

    /**
     * @return string
     */
    public function toUTF32BE()
    {
        return $this->toEncodedString(
            TransformationFormat::ofType(TransformationFormat::THIRTY_TWO_BIG_ENDIAN)
        );
    }

    /**
     * @param TransformationFormat $convertTo
     * @return string
     */
    public function toEncodedString(TransformationFormat $convertTo)
    {
        $characters = '';

        $this->traverseWith(function (Codepoint $codepoint) use ($convertTo, &$characters) {
            $characters .= $codepoint->toEncodedCharacter($convertTo);
        });

        return $characters;
    }
}