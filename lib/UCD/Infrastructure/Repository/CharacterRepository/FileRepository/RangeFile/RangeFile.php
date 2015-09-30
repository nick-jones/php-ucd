<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;

abstract class RangeFile
{
    /**
     * @var Range
     */
    protected $range;

    /**
     * @var \SplFileInfo
     */
    protected $fileInfo;

    /**
     * @var int
     */
    protected $total;

    /**
     * @param Range $range
     * @param \SplFileInfo $fileInfo
     * @param int $total
     */
    public function __construct(Range $range, \SplFileInfo $fileInfo, $total)
    {
        $this->range = $range;
        $this->fileInfo = $fileInfo;
        $this->total = $total;
    }

    /**
     * @return array
     */
    abstract public function read();

    /**
     * @param array $map
     * @return bool
     */
    abstract public function write(array $map);

    /**
     * @return Range
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * @return string
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }
}