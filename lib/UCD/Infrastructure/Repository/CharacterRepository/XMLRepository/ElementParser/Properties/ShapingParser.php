<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties;

use UCD\Unicode\Character\Properties\Shaping;
use UCD\Unicode\Character\Properties\Shaping\Joining;
use UCD\Unicode\Character\Properties\Shaping\JoiningGroup;
use UCD\Unicode\Character\Properties\Shaping\JoiningType;

class ShapingParser extends BaseParser
{
    const ATTR_JOINING_GROUP = 'jg';
    const ATTR_JOINING_TYPE = 'jt';
    const ATTR_JOIN_CONTROL = 'Join_C';

    /**
     * @return mixed
     */
    protected function parse()
    {
        $joining = $this->parseJoining();

        return new Shaping($joining);
    }

    /**
     * @return Joining
     */
    private function parseJoining()
    {
        $joiningGroup = new JoiningGroup($this->getAttribute(self::ATTR_JOINING_GROUP));
        $joiningType = new JoiningType($this->getAttribute(self::ATTR_JOINING_TYPE));
        $joinControl = $this->getBoolAttribute(self::ATTR_JOIN_CONTROL);

        return new Joining($joiningGroup, $joiningType, $joinControl);
    }
}