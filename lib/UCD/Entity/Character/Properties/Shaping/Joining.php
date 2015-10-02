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

    /**
     * @return JoiningGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return JoiningType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function isJoinControl()
    {
        return $this->joinControl;
    }
}