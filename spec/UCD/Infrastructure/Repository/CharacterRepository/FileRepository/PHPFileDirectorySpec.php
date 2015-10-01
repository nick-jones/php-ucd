<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use PhpSpec\ObjectBehavior;
use UCD\Exception\InvalidArgumentException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPFileDirectory;
use VirtualFileSystem\FileSystem;

/**
 * @mixin PHPFileDirectory
 */
class PHPFileDirectorySpec extends ObjectBehavior
{
    public function it_can_be_instantiated_with_a_valid_directory_path()
    {
        $fs = new FileSystem();
        $path = $fs->path('/db');
        mkdir($path);

        $this->beConstructedWith(new \SplFileInfo($path));

        $this->shouldHaveType(PHPFileDirectory::CLASS);
    }

    public function it_cannot_be_instantiated_with_a_non_existent_directory_path()
    {
        $fs = new FileSystem();
        $path = $fs->path('/db');

        $this->beConstructedWith(new \SplFileInfo($path));

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_cannot_be_instantiated_with_a_path_to_a_file()
    {
        $fs = new FileSystem();
        $path = $fs->path('/db');
        $file = $fs->path('/db/foo');

        mkdir($path);
        touch($file);

        $this->beConstructedWith(new \SplFileInfo($file));

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }
}