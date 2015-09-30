<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

class PHPSerializer
{
    /**
     * @param mixed $data
     * @return string
     */
    public function serialize($data)
    {
        return serialize($data);
    }

    /**
     * @param string $data
     * @return mixed
     */
    public function unserialize($data)
    {
        return unserialize($data);
    }
}