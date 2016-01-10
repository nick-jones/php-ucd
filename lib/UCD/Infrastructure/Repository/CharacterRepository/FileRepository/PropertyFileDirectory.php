<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use UCD\Exception\UnexpectedValueException;
use UCD\Unicode\AggregatorRelay;

abstract class PropertyFileDirectory
{
    /**
     * @var \SplFileInfo
     */
    protected $basePath;

    /**
     * @var PropertyFiles
     */
    private $files;

    /**
     * @param \SplFileInfo $basePath
     * @param PropertyFiles $files
     */
    public function __construct(\SplFileInfo $basePath, PropertyFiles $files)
    {
        $this->basePath = $basePath;
        $this->files = $files;
    }

    /**
     * @param Property $property
     * @return PropertyFile
     */
    abstract protected function createFileForProperty(Property $property);

    /**
     * @param Property $property
     * @return PropertyFile
     */
    public function addFileForProperty(Property $property)
    {
        $file = $this->createFileForProperty($property);

        return $this->files->add($file);
    }

    /**
     * @param Property $property
     * @return PropertyFile|null
     * @throws UnexpectedValueException
     */
    public function getFileForProperty(Property $property)
    {
        return $this->files->getByProperty($property);
    }

    /**
     * @param Property $property
     * @param Range[] $ranges
     */
    public function writeProperty(Property $property, array $ranges)
    {
        $file = $this->addFileForProperty($property);
        $file->write($ranges);
    }
}