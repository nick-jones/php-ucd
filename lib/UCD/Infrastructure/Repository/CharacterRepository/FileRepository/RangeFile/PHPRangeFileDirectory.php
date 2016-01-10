<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\FileIterator;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFiles;

class PHPRangeFileDirectory extends RangeFileDirectory
{
    /**
     * @param \SplFileInfo $basePath
     * @return PHPRangeFileDirectory
     * @throws \UCD\Exception\InvalidArgumentException
     */
    public static function fromPath(\SplFileInfo $basePath)
    {
        $files = [];
        $fileInfos = FileIterator::fromPath($basePath);

        foreach ($fileInfos as $fileInfo) {
            $file = PHPRangeFile::fromFileInfo($fileInfo);
            array_push($files, $file);
        }

        return new self($basePath, new RangeFiles($files));
    }

    /**
     * @param Range $range
     * @param int $total
     * @return RangeFile
     */
    protected function createFileFromRangeAndTotal(Range $range, $total)
    {
        return PHPRangeFile::fromRangeAndTotal($this->basePath, $range, $total);
    }
}