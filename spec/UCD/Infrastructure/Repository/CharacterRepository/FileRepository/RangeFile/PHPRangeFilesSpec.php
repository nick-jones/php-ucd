<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

use PhpSpec\ObjectBehavior;
use VirtualFileSystem\FileSystem;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFiles;

/**
 * @mixin PHPRangeFiles
 */
class PHPRangeFilesSpec extends ObjectBehavior
{
    const MOCK_DB_PATH = '/tmp';

    public function it_can_be_constructed_from_a_directory_path()
    {
        $fs = new FileSystem();
        $dbPath = new \SplFileInfo($fs->path('/db'));

        mkdir($fs->path('/db'));
        touch($fs->path('/db/00000001-00000010!0010.php'));

        $this->beConstructedThrough('fromDirectory', [$dbPath]);
        $this->shouldHaveType(PHPRangeFiles::class);
        $this->shouldHaveCount(1);
    }

    public function it_can_have_new_files_added_to_it_by_providing_details()
    {
        $total = 10;
        $dbPath = new \SplFileInfo(self::MOCK_DB_PATH);
        $fileInfo = new \SplFileInfo(sprintf('%s/00000001-00000010!0010.php', self::MOCK_DB_PATH));
        $range = new Range(1, 10);

        $this->addFromDetails($dbPath, $range, $total)
            ->shouldBeLike(new PHPRangeFile($range, $fileInfo, $total));
    }
}