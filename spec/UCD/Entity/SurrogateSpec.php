<?php

namespace spec\UCD\Entity;

use PhpSpec\ObjectBehavior;
use UCD\Entity\Character\Properties\General;
use UCD\Entity\Character\Properties\General\Block;
use UCD\Entity\Codepoint;
use UCD\Entity\Surrogate;
use UCD\Exception\InvalidArgumentException;

/**
 * @mixin Surrogate
 */
class SurrogateSpec extends ObjectBehavior
{
    public function it_can_be_instantiated_with_high_private_use_surrogates(General $properties)
    {
        $properties->getBlock()
            ->willReturn(new Block(Block::HIGH_PRIVATE_USE_SURROGATES));

        $this->beConstructedWith(Codepoint::fromInt(0), $properties);
        $this->shouldHaveType(Surrogate::class);
    }

    public function it_can_be_instantiated_with_high_surrogates(General $properties)
    {
        $properties->getBlock()
            ->willReturn(new Block(Block::HIGH_SURROGATES));

        $this->beConstructedWith(Codepoint::fromInt(0), $properties);
        $this->shouldHaveType(Surrogate::class);
    }

    public function it_can_be_instantiated_with_low_surrogates(General $properties)
    {
        $properties->getBlock()
            ->willReturn(new Block(Block::LOW_SURROGATES));

        $this->beConstructedWith(Codepoint::fromInt(0), $properties);
        $this->shouldHaveType(Surrogate::class);
    }

    public function it_cannot_be_instantiated_with_any_other_block(General $properties)
    {
        $properties->getBlock()
            ->willReturn(new Block(Block::AEGEAN_NUMBERS));

        $this->beConstructedWith(Codepoint::fromInt(0), $properties);

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }
}