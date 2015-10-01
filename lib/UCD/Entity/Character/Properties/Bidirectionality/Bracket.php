<?php

namespace UCD\Entity\Character\Properties\Bidirectionality;

use UCD\Entity\Codepoint;

abstract class Bracket
{
    /**
     * @var Codepoint
     */
    private $pairedWith;

    /**
     * @param Codepoint $pairedWith
     */
    protected function __construct(Codepoint $pairedWith)
    {
        $this->pairedWith = $pairedWith;
    }

    /**
     * @param Codepoint $pairedWith
     * @return OpenBracket
     */
    public static function createOpen(Codepoint $pairedWith)
    {
        return new OpenBracket($pairedWith);
    }

    /**
     * @param Codepoint $pairedWith
     * @return OpenBracket
     */
    public static function createClose(Codepoint $pairedWith)
    {
        return new CloseBracket($pairedWith);
    }
}