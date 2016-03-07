<?php

namespace UCD\Unicode\TransformationFormat;

use UCD\Exception\InvalidArgumentException;
use UCD\Unicode\TransformationFormat;

class StringUtility
{
    /**
     * @var array
     */
    private static $iconvMap = [
        TransformationFormat::EIGHT => 'UTF-8',
        TransformationFormat::SIXTEEN => 'UTF-16',
        TransformationFormat::SIXTEEN_BIG_ENDIAN => 'UTF-16BE',
        TransformationFormat::SIXTEEN_LITTLE_ENDIAN => 'UTF-16LE',
        TransformationFormat::THIRTY_TWO => 'UTF-32',
        TransformationFormat::THIRTY_TWO_BIG_ENDIAN => 'UTF-32BE',
        TransformationFormat::THIRTY_TWO_LITTLE_ENDIAN => 'UTF-32LE'
    ];

    /**
     * @param string $string
     * @param TransformationFormat $from
     * @param TransformationFormat $to
     * @return string
     */
    public static function convertString($string, TransformationFormat $from, TransformationFormat $to)
    {
        $in = self::mapFormat($from);
        $out = self::mapFormat($to);

        return iconv($in, $out, $string);
    }

    /**
     * @param string $character
     * @param TransformationFormat $from
     * @param TransformationFormat $to
     * @throws InvalidArgumentException
     * @return string
     */
    public static function convertCharacter($character, TransformationFormat $from, TransformationFormat $to)
    {
        $in = self::mapFormat($from);

        if (iconv_strlen($character, $in) !== 1) {
            throw new InvalidArgumentException('Single character must be provided');
        }

        return self::convertString($character, $from, $to);
    }

    /**
     * @param TransformationFormat $format
     * @return string
     * @throws InvalidArgumentException
     */
    private static function mapFormat(TransformationFormat $format)
    {
        if (!array_key_exists($format->getType(), self::$iconvMap)) {
            throw new InvalidArgumentException();
        }

        return self::$iconvMap[$format->getType()];
    }

    /**
     * @param string $string
     * @param TransformationFormat $format
     * @return string[]
     * @throws InvalidArgumentException
     */
    public static function split($string, TransformationFormat $format)
    {
        $encoding = self::mapFormat($format);
        $characters = [];

        for ($i = 0; $i < iconv_strlen($string, $encoding); $i++) {
            $character = iconv_substr($string, $i, 1, $encoding);
            array_push($characters, $character);
        }

        return $characters;
    }
}