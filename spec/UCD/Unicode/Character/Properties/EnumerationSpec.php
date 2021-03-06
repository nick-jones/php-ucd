<?php

namespace spec\UCD\Unicode\Character\Properties;

use PhpSpec\ObjectBehavior;
use UCD\Unicode\Character\Properties\Enumeration;
use UCD\Exception\InvalidArgumentException;

/**
 * @mixin Enumeration
 */
class EnumerationSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(EnumerationFixture::class);
        $this->beConstructedWith(EnumerationFixture::A);
    }

    public function it_can_be_instantiated_with_a_value_that_is_defined_as_a_constant()
    {
        $this->shouldImplement(EnumerationFixture::class);
    }

    public function it_cannot_be_instantiated_with_a_value_not_defined_as_a_constant()
    {
        $this->beConstructedWith('xyz');

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_should_expose_the_original_value()
    {
        $this->getValue()
            ->shouldEqual(EnumerationFixture::A);
    }

    public function it_can_be_cast_to_a_string()
    {
        $this->__toString()
            ->shouldEqual('a');
    }

    public function it_should_equal_an_instance_of_the_same_enumeration_holding_the_same_value()
    {
        $this->equals(new EnumerationFixture(EnumerationFixture::A))
            ->shouldEqual(true);
    }

    public function it_should_not_equal_an_instance_of_the_same_enumeration_holding_a_different_value()
    {
        $this->equals(new EnumerationFixture(EnumerationFixture::B))
            ->shouldEqual(false);
    }

    public function it_should_not_equal_an_instance_of_a_different_enumeration_holding_the_same_value()
    {
        $this->equals(new EnumerationFixtureAlt(EnumerationFixtureAlt::A))
            ->shouldEqual(false);
    }
}

class EnumerationFixture extends Enumeration
{
    const A = 'a';
    const B = 'b';
}

class EnumerationFixtureAlt extends Enumeration
{
    const A = 'a';
}