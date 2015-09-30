<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;

class PHPRangeFiles extends RangeFiles
{
    /**
     * @param \SplFileInfo $basePath
     * @return PHPRangeFile
     * @throws \UCD\Exception\InvalidArgumentException
     */
    public static function fromDirectory(\SplFileInfo $basePath)
    {
        $files = [];
        $fileInfos = self::getFileIterator($basePath);

        foreach ($fileInfos as $fileInfo) {
            $file = PHPRangeFile::fromFileInfo($fileInfo);
            array_push($files, $file);
        }

        return new self($files);
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
     * @param string $dbPath
     * @param Range $range
     * @param int $total
     * @return PHPRangeFile
     */
    public function addFromDetails($dbPath, Range $range, $total)
    {
        $file = PHPRangeFile::fromRange($dbPath, $range, $total);

        return $this->add($file);
    }
}