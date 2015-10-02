<?php

namespace UCD\Entity\Character\Properties;

use UCD\Entity\Character\Properties\Bidirectionality\Classing;
use UCD\Entity\Character\Properties\Bidirectionality\Mirroring;

class Bidirectionality
{
    /**
     * @var Classing
     */
    private $class;

    /**
     * @var Mirroring
     */
    private $mirroring;

    /**
     * @param Classing $class
     * @param Mirroring $mirroring
     * @param bool $isControl
     */
    public function __construct(Classing $class, Mirroring $mirroring, $isControl)
    {
        $this->class = $class;
        $this->mirroring = $mirroring;
    }

    /**
     * @return Classing
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return Mirroring
     */
    public function getMirroring()
    {
        return $this->mirroring;
    }
}