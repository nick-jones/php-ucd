<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Serializer;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Serializer;

class PHPSerializer implements Serializer
{
    /**
     * {@inheritDoc}
     */
    public function serialize($data)
    {
        return serialize($data);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($data)
    {
        return unserialize($data);
    }
}