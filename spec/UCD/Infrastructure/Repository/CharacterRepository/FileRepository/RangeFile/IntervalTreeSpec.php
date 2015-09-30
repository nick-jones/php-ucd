<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

use PhpSpec\ObjectBehavior;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\IntervalTree;

/**
 * @mixin IntervalTree
 */
class IntervalTreeSpec extends ObjectBehavior
{
    public function it_is_mutable()
    {
        $range = new Range(0, 10);

        $this->beConstructedWith([]);
        $this->add($range);

        $this->search(5)
            ->shouldReturn([$range]);
    }
}