<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

class FileCache
{
    /**
     * @var array
     */
    private $cache = [];

    /**
     * @param RangeFile $file
     * @return string[]
     */
    public function read(RangeFile $file) {
        $filePath = $file->getPath();
        if (array_key_exists($filePath, $this->cache)) {
            $characters = $this->cache[$filePath];
        } else {
            $characters = $file->read();
            $this->cache[$filePath] = $characters;
        }

        return $characters;
    }
}
