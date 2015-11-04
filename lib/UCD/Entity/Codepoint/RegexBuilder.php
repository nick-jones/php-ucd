<?php

namespace UCD\Entity\Codepoint;

use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Range;
use UCD\Entity\RegexProvider;

class RegexBuilder implements RegexProvider
{
    /**
     * @var string
     */
    private $regex;

    /**
     * @param Codepoint $codepoint
     */
    public function addCodepoint(Codepoint $codepoint)
    {
        $this->regex .= $this->flattenCodepoint($codepoint);
    }

    /**
     * @param Codepoint $codepoint
     * @return string
     */
    private function flattenCodepoint(Codepoint $codepoint)
    {
        return sprintf('\x{%X}', $codepoint->getValue());
    }

    /**
     * @param Range $range
     */
    public function addRange(Range $range)
    {
        $this->regex .= $this->flattenRange($range);
    }

    /**
     * @param Range $range
     * @return string
     */
    private function flattenRange(Range $range)
    {
        if ($range->representsSingleCodepoint()) {
            return $this->flattenCodepoint($range->getStart());
        }

        $start = $range->getStart();
        $end = $range->getEnd();

        return sprintf('\x{%X}-\x{%X}', $start->getValue(), $end->getValue());
    }

    /**
     * @return string
     */
    public function getCharacterClass()
    {
        return $this->regex !== null
            ? sprintf('[%s]', $this->regex)
            : '';
    }
}