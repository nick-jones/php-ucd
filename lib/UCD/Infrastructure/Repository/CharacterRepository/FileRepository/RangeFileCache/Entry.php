<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFileCache;

class Entry
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var []string
     */
    private $characters;

    /**
     * @var self|null
     */
    private $previous;

    /**
     * @var self|null
     */
    private $next;

    public function __construct($filePath, array $characters)
    {
        $this->filePath = $filePath;
        $this->characters = $characters;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @return array
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * @return Entry|null
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * @param Entry|null $previous
     */
    public function setPrevious(Entry $previous = null)
    {
        $this->previous = $previous;
    }

    /**
     * @param Entry|null $next
     */
    public function setNext(Entry $next = null)
    {
        $this->next = $next;
    }

    /**
     * @return Entry|null
     */
    public function getNext()
    {
        return $this->next;
    }
}