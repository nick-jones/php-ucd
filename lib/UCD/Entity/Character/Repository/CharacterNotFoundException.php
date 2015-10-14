<?php

namespace UCD\Entity\Character\Repository;

use UCD\Entity\Codepoint;
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
     * @return CharacterNotFoundException
     */
    public static function withCodepoint(Codepoint $codepoint)
    {
        $exception = new self();
        $exception->codepoint = $codepoint;

        return $exception;
    }

    /**
     * @param int $value
     * @return CharacterNotFoundException
     */
    public static function withCodepointValue($value)
    {
        return self::withCodepoint(Codepoint::fromInt($value));
    }
}