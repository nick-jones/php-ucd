<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFileCache\Entry;

class RangeFileCache
{
    const DEFAULT_CAPACITY = 3;

    /**
     * @var Entry[]
     */
    private $cache = [];

    /**
     * @var Entry|null
     */
    private $head;

    /**
     * @var Entry|null
     */
    private $tail;

    /**
     * @var int
     */
    private $capacity;

    public function __construct($capacity = self::DEFAULT_CAPACITY)
    {
        if ($capacity <= 1) {
            throw new \InvalidArgumentException('capacity must be greater than 1');
        }

        $this->capacity = $capacity;
    }

    /**
     * @param RangeFile $file
     * @return string[]
     */
    public function read(RangeFile $file) {
        $filePath = $file->getPath();

        if (array_key_exists($filePath, $this->cache)) {
            $entry = $this->cache[$filePath];
            $characters = $entry->getCharacters();
            $this->remove($entry);
            $this->setHead($entry);
        } else {
            if (count($this->cache) + 1 > $this->capacity) {
                $tail = $this->tail;
                $this->remove($tail);
                unset($this->cache[$tail->getFilePath()]);
            }

            $characters = $file->read();
            $entry = new Entry($filePath, $characters);
            $this->setHead($entry);
            $this->cache[$filePath] = $entry;
        }

        return $characters;
    }

    private function setHead(Entry $entry)
    {
        $entry->setNext($this->head);

        if ($this->head !== null) {
            $this->head->setPrevious($entry);
        }

        $this->head = $entry;

        if ($this->tail === null) {
            $this->tail = $entry;
        }
    }

    private function remove(Entry $entry)
    {
        $previous = $entry->getPrevious();
        $next = $entry->getNext();

        if ($previous !== null) {
            $previous->setNext($next);
        } else {
            $this->head = $next;
        }

        if ($next !== null) {
            $next->setPrevious($previous);
        } else {
            $this->tail = $previous;
        }
    }
}
