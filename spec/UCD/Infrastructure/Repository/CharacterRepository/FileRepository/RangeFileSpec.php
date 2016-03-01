<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use PhpSpec\ObjectBehavior;
use UCD\Exception\RangeException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\File;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

/**
 * @mixin RangeFile
 */
class RangeFileSpec extends ObjectBehavior
{
    public function let(File $file)
    {
        $range = new Range(1, 1);
        $this->beConstructedWith($file, $range, 1);
    }

    public function it_can_read_back_a_previous_written_map($file)
    {
        $file->readArray()
            ->willReturn($map = [1 => 'foo']);

        $this->read()
            ->shouldReturn($map);
    }

    public function it_can_write_a_map($file)
    {
        $map = [1 => 'foo'];

        $this->write($map);

        $file->writeArray($map)
            ->shouldHaveBeenCalled();
    }

    public function it_throws_if_the_size_of_the_range_to_be_written_exceeds_the_pre_specified_range_size()
    {
        $this->shouldThrow(RangeException::class)
            ->duringWrite([1 => 'foo', 2 => 'bar']);
    }
}