<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use PhpSpec\ObjectBehavior;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPRangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPRangeFiles;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use VirtualFileSystem\FileSystem;

/**
 * @mixin PHPRangeFiles
 */
class PHPRangeFilesSpec extends ObjectBehavior
{
    const MOCK_DB_PATH = '/tmp';

    public function it_can_be_constructed_from_a_directory_path()
    {
        $fs = new FileSystem();
        $dbPath = $fs->path('/');

        touch($fs->path('/00000001-00000010!0010.php'));

        $this->beConstructedThrough('fromDirectory', [$dbPath]);
        $this->shouldHaveType(PHPRangeFiles::CLASS);
        $this->shouldHaveCount(1);
    }

    public function it_can_have_new_files_added_to_it_by_providing_details()
    {
        $total = 10;
        $dbPath = self::MOCK_DB_PATH;
        $fileInfo = new \SplFileInfo(sprintf('%s/00000001-00000010!0010.php', self::MOCK_DB_PATH));
        $range = new Range(1, 10);

        $this->addFromDetails($dbPath, $range, $total)
            ->shouldBeLike(new PHPRangeFile($range, $fileInfo, $total));
    }
}