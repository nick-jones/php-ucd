<?php

namespace UCD\Unicode\Character\Properties\General;

use UCD\Unicode\Character\Properties\Enumeration;

class GeneralCategory extends Enumeration
{
    const LETTER_UPPERCASE = 'Lu';
    const LETTER_LOWERCASE = 'Ll';
    const LETTER_TITLECASE = 'Lt';
    const LETTER_MODIFIER = 'Lm';
    const LETTER_OTHER = 'Lo';
    const MARK_NONSPACING = 'Mn';
    const MARK_SPACING_COMBINING = 'Mc';
    const MARK_ENCLOSING = 'Me';
    const NUMBER_DECIMAL_DIGIT = 'Nd';
    const NUMBER_LETTER = 'Nl';
    const NUMBER_OTHER = 'No';
    const PUNCTUATION_CONNECTOR = 'Pc';
    const PUNCTUATION_DASH = 'Pd';
    const PUNCTUATION_OPEN = 'Ps';
    const PUNCTUATION_CLOSE = 'Pe';
    const PUNCTUATION_INITIAL_QUOTE = 'Pi';
    const PUNCTUATION_FINAL_QUOTE = 'Pf';
    const PUNCTUATION_OTHER = 'Po';
    const SYMBOL_MATH = 'Sm';
    const SYMBOL_CURRENCY = 'Sc';
    const SYMBOL_MODIFIER = 'Sk';
    const SYMBOL_OTHER = 'So';
    const SEPARATOR_SPACE = 'Zs';
    const SEPARATOR_LINE = 'Zl';
    const SEPARATOR_PARAGRAPH = 'Zp';
    const OTHER_CONTROL = 'Cc';
    const OTHER_FORMAT = 'Cf';
    const OTHER_SURROGATE = 'Cs';
    const OTHER_PRIVATE_USE = 'Co';
    const OTHER_NOT_ASSIGNED = 'Cn';
}