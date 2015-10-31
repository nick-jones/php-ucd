<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

interface File
{
    /**
     * @return string[]
     */
    public function readArray();

    /**
     * @param string[] $items
     * @return bool
     */
    public function writeArray(array $items);

    /**
     * @return \SplFileInfo
     */
    public function getInfo();
}