<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use PhpSpec\ObjectBehavior;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFileCache;

/**
 * @mixin RangeFileCache
 */
class RangeFileCacheSpec extends ObjectBehavior
{
    public function it_caches_characters_on_read(RangeFile $rangeFile)
    {
        $characters = [1 => 'x'];

        $rangeFile->read()
            ->shouldBeCalledTimes(1)
            ->willReturn($characters);

        $rangeFile->getPath()
            ->willReturn('/path');

        $this->read($rangeFile)
            ->shouldReturn($characters);

        $this->read($rangeFile)
            ->shouldReturn($characters);
    }

    public function it_purges_least_used(RangeFile $rf1, RangeFile $rf2, RangeFile $rf3)
    {
        $this->beConstructedWith(2);

        $rf1->read()
            ->shouldBeCalledTimes(2)
            ->willReturn([]);

        $rf1->getPath()
            ->willReturn('/path/1');

        $rf2->read()
            ->shouldBeCalledTimes(1)
            ->willReturn([]);

        $rf2->getPath()
            ->willReturn('/path/2');

        $rf3->read()
            ->shouldBeCalledTimes(1)
            ->willReturn([]);

        $rf3->getPath()
            ->willReturn('/path/3');

        $this->read($rf1);
        $this->read($rf2);
        $this->read($rf3);
        $this->read($rf1);
    }
}