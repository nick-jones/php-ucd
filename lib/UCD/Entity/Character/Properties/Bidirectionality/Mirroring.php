<?php

namespace UCD\Entity\Character\Properties\Bidirectionality;

use UCD\Entity\Character\Codepoint;

class Mirroring
{
    /**
     * @var bool
     */
    private $isMirrored;

    /**
     * @var Codepoint
     */
    private $mirroredBy;

    /**
     * @param bool $isMirrored
     * @param Codepoint $mirroredBy
     */
    public function __construct($isMirrored, Codepoint $mirroredBy = null)
    {
        $this->mirroredBy = $mirroredBy;
        $this->isMirrored = $isMirrored;
    }
}