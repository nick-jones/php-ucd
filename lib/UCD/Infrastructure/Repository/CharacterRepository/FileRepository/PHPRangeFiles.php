<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

class PHPRangeFiles extends RangeFiles
{
    /**
     * @param string $basePath
     * @return PHPRangeFiles
     */
    public static function fromDirectory($basePath)
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
     * @param string $path
     * @return \SplFileInfo[]
     */
    private static function getFileIterator($path)
    {
        $directory = new \FilesystemIterator($path, \FilesystemIterator::CURRENT_AS_FILEINFO);

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