<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository\File;

use PhpSpec\ObjectBehavior;

use UCD\Exception\UnexpectedValueException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\File\PHPFile;

use VirtualFileSystem\FileSystem;

/**
 * @mixin PHPFile
 */
class PHPFileSpec extends ObjectBehavior
{
    public function it_can_write_an_array_to_the_file_system()
    {
        $fs = new FileSystem();
        $fileInfo = new \SplFileInfo($fs->path('/r.php'));

        $this->beConstructedWith($fileInfo);

        $this->writeArray([1 => 'foo', 5 => 'bar'])
            ->shouldReturn(true);
    }

    public function it_can_read_back_an_array_from_the_file_system()
    {
        $fs = new FileSystem();
        $fileInfo = new \SplFileInfo($fs->path('/r.php'));
        $data = [1 => 'foo', 5 => 'bar', 'baz' => 'boo'];

        $this->beConstructedWith($fileInfo);
        $this->writeArray($data);

        $this->readArray()
            ->shouldReturn($data);
    }

    public function it_throws_if_the_data_read_back_from_the_file_system_is_not_an_array()
    {
        $fs = new FileSystem();
        $fileInfo = new \SplFileInfo($fs->path('/r.php'));
        $file = $fileInfo->openFile('w');
        $file->fwrite("<?php\nreturn 'foo';");

        $this->beConstructedWith($fileInfo);

        $this->shouldThrow(UnexpectedValueException::class)
            ->duringReadArray();
    }

    public function it_exposes_file_information()
    {
        $fileInfo = new \SplFileInfo('');
        $this->beConstructedWith($fileInfo);

        $this->getInfo()
            ->shouldReturn($fileInfo);
    }
}