<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFile;

use UCD\Exception\InvalidArgumentException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\File\PHPFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Property;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFile;

class PHPPropertyFile extends PropertyFile
{
    const FILE_NAME_REGEX  = '/^(?P<type>[A-Za-z_-]+)\.php\.gz$/';
    const FILE_PATH_FORMAT = '%s/%s.php.gz';

    /**
     * @param \SplFileInfo $fileInfo
     * @return PHPPropertyFile
     * @throws InvalidArgumentException
     */
    public static function fromFileInfo(\SplFileInfo $fileInfo)
    {
        if (preg_match(self::FILE_NAME_REGEX, $fileInfo->getBasename(), $matches) !== 1) {
            throw new InvalidArgumentException();
        }

        $file = new PHPFile($fileInfo);
        $property = Property::ofType($matches['type']);

        return new self($file, $property);
    }

    /**
     * @param \SplFileInfo $basePath
     * @param Property $property
     * @return self
     */
    public static function fromProperty(\SplFileInfo $basePath, Property $property)
    {
        $filePath = sprintf(self::FILE_PATH_FORMAT, $basePath->getPathname(), $property);
        $fileInfo = new \SplFileInfo($filePath);
        $file = new PHPFile($fileInfo);

        return new self($file, $property);
    }
}