<?php

namespace UCD\Unicode\Character\Properties\Shaping;

use UCD\Unicode\Character\Properties\Enumeration;

class JoiningType extends Enumeration
{
    const JOIN_CAUSING = 'C';
    const DUAL_JOINING = 'D';
    const LEFT_JOINING = 'L';
    const RIGHT_JOINING = 'R';
    const TRANSPARENT = 'T';
    const NON_JOINING = 'U';
}