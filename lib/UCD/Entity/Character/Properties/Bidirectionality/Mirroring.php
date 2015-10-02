<?php

namespace UCD\Entity\Character\Properties\Bidirectionality;

use UCD\Entity\Codepoint;

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
        $this->mirroredBy = $mirroredBy;
        $this->mirrored = $mirrored;
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