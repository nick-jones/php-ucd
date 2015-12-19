<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use UCD\Exception\UnexpectedValueException;

abstract class RangeFileDirectory
{
    /**
     * @var \SplFileInfo
     */
    protected $basePath;

    /**
     * @var RangeFiles
     */
    private $files;

    /**
     * @param \SplFileInfo $basePath
     * @param RangeFiles $files
     */
    public function __construct(\SplFileInfo $basePath, RangeFiles $files)
    {
        $this->basePath = $basePath;
        $this->files = $files;
    }

    /**
     * @param Range $range
     * @param int $total
     * @return RangeFile
     */
    abstract protected function createFileFromRangeAndTotal(Range $range, $total);

    /**
     * @param Range $range
     * @param int $total
     * @return RangeFile
     */
    public function addFileFromRangeAndTotal(Range $range, $total)
    {
        $file = $this->createFileFromRangeAndTotal($range, $total);

        return $this->files->add($file);
    }

    /**
     * @param int $value
     * @return RangeFile|null
     * @throws UnexpectedValueException
     */
    public function getFileFromValue($value)
    {
        return $this->files->getForValue($value);
    }

    /**
     * @return RangeFiles|RangeFile[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param Range $range
     * @param array $map
     */
    public function writeRange(Range $range, array $map)
    {
        $rangeFile = $this->addFileFromRangeAndTotal($range, count($map));
        $rangeFile->write($map);
    }
}