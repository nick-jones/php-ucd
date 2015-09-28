<?php

namespace UCD\Entity\Character\Properties\Normalization;

use UCD\Entity\Character\Properties\Enumeration;

class DecompositionType extends Enumeration
{
    const CANONICAL = 'can';
    const COMPAT = 'com';
    const CIRCLE = 'enc';
    const FIN = 'fin';
    const FONT = 'font';
    const FRACTION = 'fra';
    const INITIAL = 'init';
    const ISOLATED = 'iso';
    const MEDIAL = 'med';
    const NARROW = 'nar';
    const NOBREAK = 'nb';
    const NONE = 'none';
    const SMALL = 'sml';
    const SQUARE = 'sqr';
    const SUB = 'sub';
    const SUPER = 'sup';
    const VERTICAL = 'vert';
    const WIDE = 'wide';
}