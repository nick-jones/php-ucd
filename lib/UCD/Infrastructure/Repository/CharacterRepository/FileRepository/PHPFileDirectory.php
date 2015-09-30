<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use UCD\Exception\InvalidArgumentException;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFiles;

class PHPFileDirectory implements \IteratorAggregate
{
    /**
     * @var \SplFileInfo
     */
    private $path;

    /**
     * @var PHPRangeFiles
     */
    private $files;

    /**
     * @param \SplFileInfo $path
     * @throws InvalidArgumentException
     */
    public function __construct(\SplFileInfo $path)
    {
        if (!$path->isDir()) {
            throw new InvalidArgumentException(sprintf('"%s" should be a directory', $path));
        }

        $this->path = $path;
        $this->files = PHPRangeFiles::fromDirectory($path);
    }

    /**
     * @param int $value
     * @return PHPRangeFile|null
     */
    public function getFileFromValue($value)
    {
        return $this->files->getForValue($value);
    }

    /**
     * @return PHPRangeFiles|PHPRangeFile[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param Range $range
     * @param int $total
     * @return PHPRangeFile
     */
    public function createFileFromDetails(Range $range, $total)
    {
        return $this->files->addFromDetails($this->path, $range, $total);
    }

    /**
     * @return \Iterator
     */
    public function getIterator()
    {
        return $this->files->getIterator();
    }
}