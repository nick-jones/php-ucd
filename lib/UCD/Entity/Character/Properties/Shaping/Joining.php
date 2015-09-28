<?php

namespace UCD\Entity\Character\Properties\Shaping;

class Joining
{
    /**
     * @var JoiningGroup
     */
    private $group;
    /**
     * @var JoiningType
     */
    private $type;

    /**
     * @var bool
     */
    private $joinControl;

    /**
     * @param JoiningGroup $group
     * @param JoiningType $type
     * @param bool $joinControl
     */
    public function __construct(JoiningGroup $group, JoiningType $type, $joinControl)
    {
        $this->group = $group;
        $this->type = $type;
        $this->joinControl = $joinControl;
    }
}