<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

interface Serializer
{
    /**
     * @param mixed $data
     * @return string
     */
    public function serialize($data);

    /**
     * @param string $data
     * @return mixed
     */
    public function unserialize($data);
}