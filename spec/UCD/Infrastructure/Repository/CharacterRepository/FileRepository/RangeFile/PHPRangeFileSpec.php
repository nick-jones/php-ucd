<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

use PhpSpec\ObjectBehavior;
use VirtualFileSystem\FileSystem;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;

/**
 * @mixin \UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFile
 */
class PHPRangeFileSpec extends ObjectBehavior
{
    const MOCK_DB_PATH = '/tmp';

    public function it_can_be_constructed_file_path_information(\SplFileInfo $fileInfo)
    {
        $fileInfo->getBasename()
            ->willReturn('00000001-00000010!0010.php');

        $this->beConstructedThrough('fromFileInfo', [$fileInfo]);
        $this->shouldHaveType(\UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFile::CLASS);

        $this->getRange()
            ->shouldBeLike(new Range(1, 10));

        $this->getTotal()
            ->shouldReturn(10);
    }

    public function it_can_be_constructed_from_range_and_path_details()
    {
        $dbPath = self::MOCK_DB_PATH;
        $range = new Range(1, 10);
        $total = 10;

        $this->beConstructedThrough('fromRange', [$dbPath, $range, $total]);
        $this->shouldHaveType(\UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFile::CLASS);

        $this->getFileInfo()
            ->getBasename()
            ->shouldReturn('00000001-00000010!0010.php');
    }

    public function it_can_write_generated_code_to_the_file_system(\SplFileInfo $fileInfo)
    {
        $fs = new FileSystem();
        $file = new \SplFileObject($fs->path('/r.php'), 'w');

        $fileInfo->openFile('w')
            ->willReturn($file);

        $this->beConstructedWith(new Range(1, 10), $fileInfo, 10);

        $this->write([1 => 'foo', 5 => 'bar'])
            ->shouldReturn(true);
    }

    public function it_can_read_back_generated_code_from_the_file_system(\SplFileInfo $fileInfo)
    {
        $fs = new FileSystem();
        $file = new \SplFileObject($fs->path('/r.php'), 'w');
        $data = [1 => 'foo', 5 => 'bar'];

        $fileInfo->__toString()
            ->willReturn($fs->path('/r.php'));

        $fileInfo->openFile('w')
            ->willReturn($file);

        $this->beConstructedWith(new Range(1, 10), $fileInfo, 10);
        $this->write($data);

        $this->read()
            ->shouldReturn($data);
    }
}