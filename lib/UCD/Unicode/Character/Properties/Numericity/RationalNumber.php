<?php

namespace UCD\Unicode\Character\Properties\Numericity;

use UCD\Exception\InvalidArgumentException;

class RationalNumber
{
    const REGEX_FRACTION = '#^(?P<sign>[+-]?)(?P<numerator>[0-9]+)(/(?P<denominator>[0-9]+))?$#';

    /**
     * @var int
     */
    private $numerator;

    /**
     * @var int
     */
    private $denominator;

    /**
     * @var bool
     */
    private $negative;

    /**
     * @param int $numerator
     * @param int $denominator
     * @param bool $negative
     * @throws InvalidArgumentException
     */
    public function __construct($numerator, $denominator, $negative)
    {
        if ($denominator === 0) {
            throw new InvalidArgumentException('Zero denominator');
        }

        $this->numerator = $numerator;
        $this->denominator = $denominator;
        $this->negative = $negative;
    }

    /**
     * @return bool
     */
    public function isFraction()
    {
        return $this->denominator !== 1;
    }

    /**
     * @return int
     */
    public function getNumerator()
    {
        return $this->numerator;
    }

    /**
     * @return int
     */
    public function getDenominator()
    {
        return $this->denominator;
    }

    /**
     * @return boolean
     */
    public function isNegative()
    {
        return $this->negative;
    }

    /**
     * @param string $value
     * @return RationalNumber
     * @throws InvalidArgumentException
     */
    public static function fromString($value)
    {
        if (preg_match(self::REGEX_FRACTION, $value, $matches) !== 1) {
            throw new InvalidArgumentException('Invalid number');
        }

        $numerator = $matches['numerator'];
        $denominator = isset($matches['denominator']) ? $matches['denominator'] : 1;
        $isNegative = isset($matches['sign']) && $matches['sign'] === '-';

        return new self($numerator, $denominator, $isNegative);
    }

    /**
     * @param self $number
     * @return string
     */
    public static function toString(self $number)
    {
        $string = $number->isNegative() ? '-' : '';
        $string .= $number->getNumerator();

        if ($number->isFraction()) {
            $string .= sprintf('/%d', $number->getDenominator());
        }

        return $string;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return self::toString($this);
    }
}