<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use UCD\Exception\InvalidArgumentException;

class PropertyFile
{
    /**
     * @var File
     */
    private $file;

    /**
     * @var Property
     */
    private $property;

    /**
     * @param File $file
     * @param Property $property
     */
    public function __construct(File $file, Property $property)
    {
        $this->file = $file;
        $this->property = $property;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @return int[]
     */
    public function read()
    {
        return $this->file->readArray();
    }

    /**
     * @param int[] $map
     * @return bool
     * @throws InvalidArgumentException
     */
    public function write(array $map)
    {
        if (count($map) === 0) {
            throw new InvalidArgumentException();
        }

        return $this->file->writeArray($map);
    }
}
