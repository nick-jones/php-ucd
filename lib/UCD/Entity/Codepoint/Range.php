<?php

namespace UCD\Entity\Codepoint;

use UCD\Entity\Codepoint;
use UCD\Exception\InvalidRangeException;

class Range
{
    /**
     * @var Codepoint
     */
    private $start;

    /**
     * @var Codepoint
     */
    private $end;

    /**
     * @param Codepoint $start
     * @param Codepoint $end
     * @throws InvalidRangeException
     */
    public function __construct(Codepoint $start, Codepoint $end)
    {
        if ($start->getValue() > $end->getValue()) {
            throw new InvalidRangeException();
        }

        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return Codepoint
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return Codepoint
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return bool
     */
    public function representsSingleCodepoint()
    {
        return $this->start->equals($this->end);
    }

    /**
     * @return Codepoint[]|Collection
     */
    public function expand()
    {
        return new Collection(
            $this->allCodepoints()
        );
    }

    /**
     * @return \Generator
     */
    private function allCodepoints()
    {
        $start = $this->getStart()->getValue();
        $end = $this->getEnd()->getValue();

        for ($i = $start; $i <= $end; $i++) {
            yield Codepoint::fromInt($i);
        }
    }
}