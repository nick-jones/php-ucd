<?php

namespace UCD\Unicode\Character\Repository;

use UCD\Unicode\Codepoint;
use UCD\Exception;

class CharacterNotFoundException extends Exception
{
    /**
     * @var Codepoint
     */
    protected $codepoint;

    /**
     * @return Codepoint
     */
    public function getCodepoint()
    {
        return $this->codepoint;
    }

    /**
     * @param Codepoint $codepoint
     * @return self
     */
    public static function withCodepoint(Codepoint $codepoint)
    {
        $exception = new self();
        $exception->codepoint = $codepoint;

        return $exception;
    }
}