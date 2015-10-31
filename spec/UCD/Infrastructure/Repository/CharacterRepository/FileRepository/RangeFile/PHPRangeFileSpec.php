<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

use PhpSpec\ObjectBehavior;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFile;

/**
 * @mixin PHPRangeFile
 */
class PHPRangeFileSpec extends ObjectBehavior
{
    const MOCK_DB_PATH = '/tmp';

    public function it_can_be_constructed_file_path_information()
    {
        $fileInfo = new \SplFileInfo(sprintf('%s/00000001-00000010!0010.php', self::MOCK_DB_PATH));

        $this->beConstructedThrough('fromFileInfo', [$fileInfo]);
        $this->shouldHaveType(PHPRangeFile::class);

        $this->getRange()
            ->shouldBeLike(new Range(1, 10));

        $this->getTotal()
            ->shouldReturn(10);
    }

    public function it_can_be_constructed_from_range_and_path_details()
    {
        $dbPath = new \SplFileInfo(self::MOCK_DB_PATH);
        $range = new Range(1, 10);
        $total = 10;

        $this->beConstructedThrough('fromRangeAndTotal', [$dbPath, $range, $total]);
        $this->shouldHaveType(PHPRangeFile::class);
    }
}