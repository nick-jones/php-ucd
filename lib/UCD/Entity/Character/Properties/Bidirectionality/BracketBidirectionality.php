<?php

namespace UCD\Entity\Character\Properties\Bidirectionality;

use UCD\Entity\Character\Properties\Bidirectionality;
use UCD\Entity\Character\Properties\Bidirectionality\Bracket;
use UCD\Entity\Character\Properties\Bidirectionality\Classing;
use UCD\Entity\Character\Properties\Bidirectionality\Mirroring;

class BracketBidirectionality extends Bidirectionality
{
    /**
     * @var Bracket
     */
    private $bracket;

    /**
     * @param Classing $class
     * @param Mirroring $mirroring
     * @param bool $isControl
     * @param Bracket $bracket
     */
    public function __construct(Classing $class, Mirroring $mirroring, $isControl, Bracket $bracket)
    {
        $this->bracket = $bracket;

        parent::__construct($class, $mirroring, $isControl);
    }

    /**
     * @return Bracket
     */
    public function getBracket()
    {
        return $this->bracket;
    }
}