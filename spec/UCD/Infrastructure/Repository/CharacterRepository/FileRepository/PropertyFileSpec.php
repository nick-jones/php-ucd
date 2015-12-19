<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use UCD\Exception\InvalidArgumentException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\File;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Property;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFile;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Codepoint\Range;

/**
 * @mixin PropertyFile
 */
class PropertyFileSpec extends ObjectBehavior
{
    public function let(File $file, Property $property)
    {
        $this->beConstructedWith($file, $property);
    }

    public function it_exposes_the_underlying_file($file)
    {
        $this->getFile()
            ->shouldReturn($file);
    }

    public function it_can_read_back_a_previous_written_map_of_properties($file)
    {
        $file->readArray()
            ->willReturn($items = ['foo' => 's:0:"";']);

        $this->read()
            ->shouldReturn($items);
    }

    public function it_can_write_a_map_of_properties($file)
    {
        $items = ['foo' => 's:0:"";'];

        $this->write($items);

        $file->writeArray($items)
            ->shouldHaveBeenCalled();
    }

    public function it_throws_if_an_array_of_codepoints_to_be_written_is_empty()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->duringWrite([]);
    }
}
