<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;

use UCD\Exception\UnexpectedValueException;

class RangeFiles implements \IteratorAggregate, \Countable
{
    /**
     * @var RangeFile[]|\SplObjectStorage
     */
    private $map;

    /**
     * @var IntervalTree
     */
    private $tree;

    /**
     * @param RangeFile[] $files
     */
    public function __construct(array $files = [])
    {
        $ranges = [];
        $map = new \SplObjectStorage();

        foreach ($files as $file) {
            $map->attach($file->getRange(), $file);
            array_push($ranges, $file->getRange());
        }

        $this->map = $map;
        $this->tree = new IntervalTree($ranges);
    }

    /**
     * @param RangeFile $file
     * @return RangeFile
     */
    public function add(RangeFile $file)
    {
        $this->map->attach($file->getRange(), $file);
        $this->tree->add($file->getRange());

        return $file;
    }

    /**
     * @param int $value
     * @return RangeFile|null
     * @throws UnexpectedValueException
     */
    public function getForValue($value)
    {
        $ranges = $this->tree->search($value);
        $count = count($ranges);

        if ($count === 0) {
            return null;
        }

        if ($count > 1) {
            throw new UnexpectedValueException();
        }

        $range = array_shift($ranges);

        return $this->map[$range];
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getAllFiles());
    }

    /**
     * @return RangeFile[]
     */
    private function getAllFiles()
    {
        $all = [];
        $this->map->rewind();

        while ($this->map->valid() === true) {
            array_push($all, $this->map->getInfo());
            $this->map->next();
        }

        return $all;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->map);
    }
}