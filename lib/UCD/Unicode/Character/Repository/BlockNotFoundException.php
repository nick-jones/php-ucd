<?php

namespace UCD\Unicode\Character\Repository;

use UCD\Exception;
use UCD\Unicode\Character\Properties\General\Block;

class BlockNotFoundException extends Exception
{
    /**
     * @var Block
     */
    protected $block;

    /**
     * @return Block
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param Block $block
     * @return self
     */
    public static function withBlock(Block $block)
    {
        $exception = new self();
        $exception->block = $block;

        return $exception;
    }
}