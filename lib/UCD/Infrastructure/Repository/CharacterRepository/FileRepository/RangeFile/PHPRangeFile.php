<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

use UCD\Exception\InvalidArgumentException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\File\PHPFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

class PHPRangeFile extends RangeFile
{
    const FILE_NAME_REGEX  = '/^(?P<start>\d+)-(?P<end>\d+)!(?P<total>\d+)\.php\.gz$/';
    const FILE_PATH_FORMAT = '%s/%08d-%08d!%04d.php.gz';

    /**
     * @param \SplFileInfo $fileInfo
     * @return PHPRangeFile
     * @throws InvalidArgumentException
     */
    public static function fromFileInfo(\SplFileInfo $fileInfo)
    {
        if (preg_match(self::FILE_NAME_REGEX, $fileInfo->getBasename(), $matches) !== 1) {
            throw new InvalidArgumentException();
        }

        $range = new Range(
            (int)$matches['start'],
            (int)$matches['end']
        );

        $file = new PHPFile($fileInfo);
        $total = (int)$matches['total'];

        return new self($file, $range, $total);
    }

    /**
     * @param \SplFileInfo $basePath
     * @param Range $range
     * @param int $total
     * @return PHPRangeFile
     */
    public static function fromRangeAndTotal(\SplFileInfo $basePath, Range $range, $total)
    {
        $filePath = self::generateFilePath($basePath, $range, $total);
        $fileInfo = new \SplFileInfo($filePath);
        $file = new PHPFile($fileInfo);

        return new self($file, $range, $total);
    }

    /**
     * @param \SplFileInfo $basePath
     * @param Range $range
     * @param int $total
     * @return string
     */
    private static function generateFilePath(\SplFileInfo $basePath, Range $range, $total)
    {
        return sprintf(
            self::FILE_PATH_FORMAT,
            $basePath->getPathname(),
            $range->getStart(),
            $range->getEnd() - 1,
            $total
        );
    }
}