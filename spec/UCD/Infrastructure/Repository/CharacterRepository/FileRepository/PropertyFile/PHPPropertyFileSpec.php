<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFile;

use PhpSpec\ObjectBehavior;
use UCD\Exception\InvalidArgumentException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Property;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFile\PHPPropertyFile;

/**
 * @mixin PHPPropertyFile
 */
class PHPPropertyFileSpec extends ObjectBehavior
{
    public function it_can_be_constructed_file_path_information()
    {
        $fileInfo = new \SplFileInfo('/props/block.php.gz');

        $this->beConstructedThrough('fromFileInfo', [$fileInfo]);
        $this->shouldImplement(PHPPropertyFile::class);

        $this->getProperty()
            ->shouldBeLike(Property::ofType(Property::BLOCK));
    }

    public function it_throws_if_an_invalid_path_is_supplied()
    {
        $fileInfo = new \SplFileInfo('/db/foo.txt');

        $this->beConstructedThrough('fromFileInfo', [$fileInfo]);
        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_can_be_constructed_from_a_property()
    {
        $dbPath = new \SplFileInfo('/props');
        $property = Property::ofType(Property::BLOCK);

        $this->beConstructedThrough('fromProperty', [$dbPath, $property]);
        $this->shouldImplement(PHPPropertyFile::class);
    }
}