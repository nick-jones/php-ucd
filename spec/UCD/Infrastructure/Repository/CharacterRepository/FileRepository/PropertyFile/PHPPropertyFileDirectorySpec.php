<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFile;

use PhpSpec\ObjectBehavior;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\File\PHPFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Property;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFile\PHPPropertyFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFile\PHPPropertyFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFiles;
use VirtualFileSystem\FileSystem;

/**
 * @mixin PHPPropertyFileDirectory
 */
class PHPPropertyFileDirectorySpec extends ObjectBehavior
{
    public function it_can_be_constructed_from_a_directory_path()
    {
        $fs = new FileSystem();
        $fs->createDirectory('/props');
        $fs->createFile('/props/block.php');
        $dbPath = new \SplFileInfo($fs->path('/props'));

        $this->beConstructedThrough('fromPath', [$dbPath]);
        $this->shouldImplement(PHPPropertyFileDirectory::class);
    }

    public function it_can_have_new_files_added_to_it_by_property_details()
    {
        $dbPath = new \SplFileInfo('/props');
        $fileInfo = new \SplFileInfo('/props/block.php');
        $propertyFiles = new PropertyFiles();
        $property = Property::ofType(Property::BLOCK);

        $this->beConstructedWith($dbPath, $propertyFiles);

        $this->addFileForProperty($property)
            ->shouldBeLike(new PHPPropertyFile(new PHPFile($fileInfo), $property));
    }
}