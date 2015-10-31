<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFiles;

class PHPRangeFileDirectory extends RangeFileDirectory
{
    const FILE_NAME_REGEX  = '/^(?P<start>\d+)-(?P<end>\d+)!(?P<total>\d+)\.php$/';
    const FILE_PATH_FORMAT = '%s/%08d-%08d!%04d.php';

    /**
     * @param \SplFileInfo $basePath
     * @return PHPRangeFileDirectory
     * @throws \UCD\Exception\InvalidArgumentException
     */
    public static function fromPath(\SplFileInfo $basePath)
    {
        $files = [];
        $fileInfos = self::getFileIterator($basePath);

        foreach ($fileInfos as $fileInfo) {
            $file = PHPRangeFile::fromFileInfo($fileInfo);
            array_push($files, $file);
        }

        return new self($basePath, new RangeFiles($files));
    }

    /**
     * @param \SplFileInfo $path
     * @return \SplFileInfo[]
     */
    private static function getFileIterator(\SplFileInfo $path)
    {
        $pathName = $path->getPathname();

        if (strpos($path, ':') === strlen($pathName) - 1) {
            $pathName = sprintf('%s//', $pathName);
        }

        $directory = new \FilesystemIterator($pathName, \FilesystemIterator::CURRENT_AS_FILEINFO);

        return new \CallbackFilterIterator($directory, function (\SplFileInfo $file) {
            return $file->isFile();
        });
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