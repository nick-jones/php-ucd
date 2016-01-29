<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use PhpSpec\ObjectBehavior;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\FileIterator;
use VirtualFileSystem\FileSystem;

class FileIteratorSpec extends ObjectBehavior
{
    public function it_can_be_instantiated_from_a_directory_path()
    {
        $fs = new FileSystem();
        $fileInfo = new \SplFileInfo($fs->path('/'));

        $this->beConstructedThrough('fromPath', [$fileInfo]);
        $this->shouldHaveType(FileIterator::class);
    }

    public function it_only_presents_files_during_iteration()
    {
        $fs = new FileSystem();
        $fs->createDirectory('/foo');
        $fs->createDirectory('/foo/bar');
        $fs->createFile('/foo/baz');
        $filePath = $fs->path('/foo/baz');
        $dir = new \SplFileInfo($fs->path('/foo'));

        $this->beConstructedThrough('fromPath', [$dir]);
        $this->shouldIterateLike([
            $filePath => new \SplFileInfo($filePath)
        ]);
    }
}