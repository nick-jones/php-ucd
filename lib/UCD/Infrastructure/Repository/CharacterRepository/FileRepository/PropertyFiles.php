<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use UCD\Exception\UnexpectedValueException;

class PropertyFiles
{
    /**
     * @var PropertyFile[]
     */
    private $files = [];

    /**
     * @param PropertyFile $file
     * @return PropertyFile
     */
    public function add(PropertyFile $file)
    {
        $key = $this->keyFromProperty(
            $file->getProperty()
        );

        $this->files[$key] = $file;
    }

    /**
     * @param Property $property
     * @return PropertyFile
     * @throws UnexpectedValueException
     */
    public function getByProperty(Property $property)
    {
        $key = $this->keyFromProperty($property);

        if (!array_key_exists($key, $this->files)) {
            throw new UnexpectedValueException();
        }

        return $this->files[$key];
    }

    /**
     * @param Property $property
     * @return string
     */
    private function keyFromProperty(Property $property)
    {
        return (string)$property;
    }
}