<?php

namespace UCD\Unicode\Character\Properties\Bidirectionality;

use UCD\Unicode\Codepoint;

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
     * @return Codepoint
     */
    public function getPairedWith()
    {
        return $this->pairedWith;
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