<?php

namespace UCD\Unicode\Character\Properties\Bidirectionality;

use UCD\Unicode\Codepoint;

class Mirroring
{
    /**
     * @var bool
     */
    private $mirrored;

    /**
     * @var Codepoint
     */
    private $mirroredBy;

    /**
     * @param bool $mirrored
     * @param Codepoint $mirroredBy
     */
    public function __construct($mirrored, Codepoint $mirroredBy = null)
    {
        $this->mirrored = $mirrored;
        $this->mirroredBy = $mirroredBy;
    }

    /**
     * @return bool
     */
    public function isMirrored()
    {
        return $this->mirrored;
    }

    /**
     * @return Codepoint
     */
    public function getMirroredBy()
    {
        return $this->mirroredBy;
    }
}