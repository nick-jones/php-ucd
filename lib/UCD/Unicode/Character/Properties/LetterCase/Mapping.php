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
     * @var Codepoint[]
     */
    private $standard;

    /**
     * @param Codepoint $simple
     * @param Codepoint[] $standard
     */
    public function __construct(Codepoint $simple, array $standard)
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
     * @return Codepoint\Collection|Codepoint[]
     */
    public function getStandard()
    {
        return Codepoint\Collection::fromArray(
            $this->standard
        );
    }
}