<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

use PhpSpec\ObjectBehavior;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\File\PHPFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFiles;

use VirtualFileSystem\FileSystem;

/**
 * @mixin PHPRangeFileDirectory
 */
class PHPRangeFileDirectorySpec extends ObjectBehavior
{
    const MOCK_DB_PATH = '/tmp';

    public function it_can_be_constructed_from_a_directory_path()
    {
        $fs = new FileSystem();
        $dbPath = new \SplFileInfo($fs->path('/db'));

        mkdir($fs->path('/db'));
        touch($fs->path('/db/00000001-00000010!0010.php'));

        $this->beConstructedThrough('fromPath', [$dbPath]);
        $this->shouldImplement(PHPRangeFileDirectory::class);

        $this->getFiles()
            ->shouldHaveCount(1);
    }

    public function it_can_have_new_files_added_to_it_by_range_details()
    {
        $total = 10;
        $dbPath = new \SplFileInfo(self::MOCK_DB_PATH);
        $fileInfo = new \SplFileInfo(sprintf('%s/00000001-00000010!0010.php', self::MOCK_DB_PATH));
        $range = new Range(1, 10);

        $this->beConstructedWith($dbPath, new RangeFiles());

        $this->addFileFromRangeAndTotal($range, $total)
            ->shouldBeLike(new PHPRangeFile(new PHPFile($fileInfo), $range, $total));

        $this->getFiles()
            ->shouldHaveCount(1);
    }
}