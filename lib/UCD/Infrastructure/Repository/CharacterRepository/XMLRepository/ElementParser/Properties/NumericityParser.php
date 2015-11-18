<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties;

use UCD\Unicode\Character\Properties\Numericity;
use UCD\Unicode\Character\Properties\Numericity\NonNumeric;
use UCD\Unicode\Character\Properties\Numericity\Numeric;
use UCD\Unicode\Character\Properties\Numericity\NumericType;
use UCD\Unicode\Character\Properties\Numericity\RationalNumber;

class NumericityParser extends BaseParser
{
    const NOT_A_NUMBER = 'NaN';
    const ATTR_NUMERIC_TYPE = 'nt';
    const ATTR_NUMERIC_VALUE = 'nv';

    /**
     * @return Numericity
     */
    protected function parse()
    {
        $numericType = $this->parseNumericType();
        $numericValue = $this->getAttribute(self::ATTR_NUMERIC_VALUE);

        if ($numericValue === self::NOT_A_NUMBER) {
            return new NonNumeric($numericType);
        }

        $numericValue = RationalNumber::fromString($numericValue);

        return new Numeric($numericType, $numericValue);
    }

    /**
     * @return NumericType
     */
    private function parseNumericType()
    {
        return new NumericType($this->getAttribute(self::ATTR_NUMERIC_TYPE));
    }
}