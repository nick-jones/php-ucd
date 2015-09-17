<?php

namespace UCD\Entity\Character\Properties\Numericity;

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
    private $isNegative;

    /**
     * @param int $numerator
     * @param int $denominator
     * @param bool $isNegative
     * @throws InvalidArgumentException
     */
    public function __construct($numerator, $denominator, $isNegative)
    {
        if ($denominator === 0) {
            throw new InvalidArgumentException('Zero denominator');
        }

        $this->numerator = $numerator;
        $this->denominator = $denominator;
        $this->isNegative = $isNegative;
    }

    /**
     * @return bool
     */
    public function isFraction()
    {
        return $this->denominator !== 1;
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
}