<?php

namespace UCD\Unicode\Character\Properties;

use UCD\Unicode\Comparable;
use UCD\Exception\InvalidArgumentException;

abstract class Enumeration implements Comparable
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    private static $constantsCache = [];

    /**
     * @param mixed $value
     * @throws InvalidArgumentException
     */
    final public function __construct($value)
    {
        if ($this->isKnown($value) !== true) {
            throw new InvalidArgumentException(sprintf('"%s" is not recognised', var_export($value, true)));
        }

        $this->value = $value;
    }

    /**
     * @param string $value
     * @return static
     */
    final public static function fromValue($value)
    {
        return new static($value);
    }

    /**
     * @return mixed
     */
    final public function getValue()
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

        return $other instanceof static
            && $other->value === $this->value;
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isKnown($value)
    {
        return in_array($value, self::getConstants(), true);
    }

    /**
     * @return array
     */
    private static function getConstants()
    {
        $className = static::class;

        if (!array_key_exists($className, self::$constantsCache)) {
            self::$constantsCache[$className] = self::getConstantsForClass($className);
        }

        return self::$constantsCache[$className];
    }

    /**
     * @param string $className
     * @return array
     */
    private static function getConstantsForClass($className)
    {
        return (new \ReflectionClass($className))
            ->getConstants();
    }
}