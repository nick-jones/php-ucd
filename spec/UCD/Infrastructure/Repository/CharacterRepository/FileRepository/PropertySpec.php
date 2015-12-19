<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use UCD\Exception\InvalidArgumentException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Property;

/**
 * @mixin Property
 */
class PropertySpec extends ObjectBehavior
{
    public function it_throws_if_an_invalid_property_name_is_supplied()
    {
        $this->beConstructedThrough('withName', ['foo']);

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_can_be_instantiated_with_a_valid_property_name()
    {
        $this->beConstructedThrough('withName', [Property::PROPERTY_BLOCK]);

        $this->shouldHaveType(Property::class);
    }

    public function it_can_be_cast_to_a_string()
    {
        $this->beConstructedThrough('withName', [Property::PROPERTY_BLOCK]);

        $this->__toString()
            ->shouldReturn('block');
    }
}
