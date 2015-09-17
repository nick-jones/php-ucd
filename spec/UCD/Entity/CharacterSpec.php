<?php

namespace spec\UCD\Entity;

use PhpSpec\ObjectBehavior;
use UCD\Entity\Character;
use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\Properties;
use UCD\Entity\Comparable;

/**
 * @mixin Character
 */
class CharacterSpec extends ObjectBehavior
{
    public function let(Codepoint $codepoint, Properties $properties)
    {
        $this->beConstructedWith($codepoint, $properties);
    }

    public function it_should_be_comparable()
    {
        $this->shouldHaveType(Comparable::CLASS);
    }

    public function it_should_equal_an_instance_with_the_same_codepoint($properties)
    {
        $codepointA = Codepoint::fromInt(10);
        $codepointB = Codepoint::fromInt(10);

        $this->beConstructedWith($codepointA, $properties);

        $this->equals(new Character($codepointB, $properties->getWrappedObject()))
            ->shouldReturn(true);
    }

    public function it_should_not_equal_an_instance_with_a_different_codepoint($properties)
    {
        $codepointA = Codepoint::fromInt(10);
        $codepointB = Codepoint::fromInt(11);

        $this->beConstructedWith($codepointA, $properties);

        $this->equals(new Character($codepointB, $properties->getWrappedObject()))
            ->shouldReturn(false);
    }

    public function it_should_expose_its_codepoint($codepoint)
    {
        $this->getCodepoint()
            ->shouldReturn($codepoint);
    }

    public function it_should_exposes_its_properties($properties)
    {
        $this->getProperties()
            ->shouldReturn($properties);
    }
}