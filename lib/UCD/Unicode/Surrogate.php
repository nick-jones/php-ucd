<?php

namespace UCD\Unicode;

use UCD\Unicode\Character\Properties\General;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Exception\InvalidArgumentException;

class Surrogate extends NonCharacter
{
    /**
     * @param Codepoint $codepoint
     * @param General $generalProperties
     * @throws InvalidArgumentException
     */
    public function __construct(Codepoint $codepoint, General $generalProperties)
    {
        if (!$this->isValidBlock($generalProperties->getBlock())) {
            throw new InvalidArgumentException('Surrogate should reside within a surrogate block');
        }

        parent::__construct($codepoint, $generalProperties);
    }

    /**
     * @param Block $block
     * @return bool
     */
    private function isValidBlock(Block $block)
    {
        static $valid = [
            Block::HIGH_PRIVATE_USE_SURROGATES => true,
            Block::HIGH_SURROGATES => true,
            Block::LOW_SURROGATES => true
        ];

        return array_key_exists($block->getValue(), $valid);
    }
}