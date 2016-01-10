<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFile;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\FileIterator;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Property;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFiles;

class PHPPropertyFileDirectory extends PropertyFileDirectory
{
    /**
     * @param \SplFileInfo $basePath
     * @return PHPPropertyFileDirectory
     */
    public static function fromPath(\SplFileInfo $basePath)
    {
        $files = [];
        $fileInfos = FileIterator::fromPath($basePath);

        foreach ($fileInfos as $fileInfo) {
            $file = PHPPropertyFile::fromFileInfo($fileInfo);
            array_push($files, $file);
        }

        return new self($basePath, new PropertyFiles($files));
    }

    /**
     * @param Property $property
     * @return PropertyFile
     */
    protected function createFileForProperty(Property $property)
    {
        return PHPPropertyFile::fromProperty($this->basePath, $property);
    }
}