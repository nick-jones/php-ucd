<?php

namespace UCD\Unicode\Codepoint;

use UCD\Unicode\Codepoint;
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
    protected function __construct(Codepoint $start, Codepoint $end)
    {
        if ($start->getValue() > $end->getValue()) {
            throw new InvalidRangeException();
        }

        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @param Codepoint $start
     * @param Codepoint $end
     * @return self
     */
    public static function between(Codepoint $start, Codepoint $end)
    {
        return new self($start, $end);
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
            $this->yieldCodepoints()
        );
    }

    /**
     * @return \Generator
     */
    private function yieldCodepoints()
    {
        $start = $this->getStart()->getValue();
        $end = $this->getEnd()->getValue();

        for ($i = $start; $i <= $end; $i++) {
            yield Codepoint::fromInt($i);
        }
    }
}