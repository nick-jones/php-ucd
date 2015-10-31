<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use UCD\Exception\RangeException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\File;

class RangeFile
{
    /**
     * @var File
     */
    private $file;

    /**
     * @var Range
     */
    private $range;

    /**
     * @var int
     */
    private $total;

    /**
     * @param File $file
     * @param Range $range
     * @param int $total
     */
    public function __construct(File $file, Range $range, $total)
    {
        $this->file = $file;
        $this->range = $range;
        $this->total = $total;
    }

    /**
     * @return string[]
     */
    public function read()
    {
        return $this->file->readArray();
    }

    /**
     * @param string[] $map
     * @return bool
     * @throws RangeException
     */
    public function write(array $map)
    {
        if (count($map) > $this->total) {
            throw new RangeException();
        }

        return $this->file->writeArray($map);
    }

    /**
     * @return Range
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }
}