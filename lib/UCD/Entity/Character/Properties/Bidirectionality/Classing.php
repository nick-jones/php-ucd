<?php

namespace UCD\Entity\Character\Properties\Bidirectionality;

use UCD\Entity\Character\Properties\Enumeration;

class Classing extends Enumeration
{
    const ARABIC_LETTER = 'AL';
    const ARABIC_NUMBER = 'AN';
    const PARAGRAPH_SEPARATOR = 'B';
    const BOUNDARY_NEUTRAL = 'BN';
    const COMMON_SEPARATOR = 'CS';
    const EUROPEAN_NUMBER = 'EN';
    const EUROPEAN_SEPARATOR = 'ES';
    const EUROPEAN_TERMINATOR = 'ET';
    const FIRST_STRONG_ISOLATE = 'FSI';
    const LEFT_TO_RIGHT = 'L';
    const LEFT_TO_RIGHT_EMBEDDING = 'LRE';
    const LEFT_TO_RIGHT_ISOLATE = 'LRI';
    const LEFT_TO_RIGHT_OVERRIDE = 'LRO';
    const NON_SPACING_MARK = 'NSM';
    const OTHER_NEUTRAL = 'ON';
    const POP_DIRECTIONAL_FORMAT = 'PDF';
    const POP_DIRECTIONAL_ISOLATE = 'PDI';
    const RIGHT_TO_LEFT = 'R';
    const RIGHT_TO_LEFT_EMBEDDING = 'RLE';
    const RIGHT_TO_LEFT_ISOLATE = 'RLI';
    const RIGHT_TO_LEFT_OVERRIDE = 'RLO';
    const SEGMENT_SEPARATOR = 'S';
    const WHITE_SPACE = 'WS';
}