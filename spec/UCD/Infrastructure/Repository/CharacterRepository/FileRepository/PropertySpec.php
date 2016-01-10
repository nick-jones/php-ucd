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
        $this->beConstructedThrough('ofType', ['foo']);

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_can_be_instantiated_with_a_valid_property_name()
    {
        $this->beConstructedThrough('ofType', [Property::BLOCK]);

        $this->shouldHaveType(Property::class);
    }

    public function it_can_be_cast_to_a_string()
    {
        $this->beConstructedThrough('ofType', [Property::BLOCK]);

        $this->__toString()
            ->shouldReturn('block');
    }
}
