<?php

namespace UCD\Unicode\Character\Properties\LetterCase;

use UCD\Unicode\Codepoint;

class Mapping
{
    /**
     * @var Codepoint
     */
    private $simple;

    /**
     * @var Codepoint\Collection
     */
    private $standard;

    public function __construct(Codepoint $simple, Codepoint\Collection $standard)
    {
        $this->simple = $simple;
        $this->standard = $standard;
    }

    /**
     * @return Codepoint
     */
    public function getSimple()
    {
        return $this->simple;
    }

    /**
     * @return Codepoint\Collection
     */
    public function getStandard()
    {
        return $this->standard;
    }
}