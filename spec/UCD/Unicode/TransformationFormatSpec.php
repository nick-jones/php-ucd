<?php

namespace spec\UCD\Unicode;

use PhpSpec\ObjectBehavior;
use UCD\Exception\InvalidArgumentException;
use UCD\Unicode\Comparable;
use UCD\Unicode\TransformationFormat;

/**
 * @mixin TransformationFormat
 */
class TransformationFormatSpec extends ObjectBehavior
{
    public function it_throws_if_an_invalid_property_name_is_supplied()
    {
        $this->beConstructedThrough('ofType', ['foo']);

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_can_be_instantiated_with_a_valid_property_name()
    {
        $this->beConstructedThrough('ofType', [TransformationFormat::SIXTEEN]);

        $this->shouldHaveType(TransformationFormat::class);
    }

    public function it_exposes_its_type()
    {
        $this->beConstructedThrough('ofType', [TransformationFormat::SIXTEEN]);

        $this->getType()
            ->shouldReturn(TransformationFormat::SIXTEEN);
    }

    public function it_should_be_comparable()
    {
        $this->beConstructedThrough('ofType', [TransformationFormat::EIGHT]);

        $this->shouldImplement(Comparable::class);
    }

    public function it_should_equal_an_instance_with_the_same_value()
    {
        $this->beConstructedThrough('ofType', [TransformationFormat::EIGHT]);

        $this->equals(TransformationFormat::ofType(TransformationFormat::EIGHT))
            ->shouldReturn(true);
    }

    public function it_should_not_equal_an_instance_with_a_different_value()
    {
        $this->beConstructedThrough('ofType', [TransformationFormat::SIXTEEN]);

        $this->equals(TransformationFormat::ofType(TransformationFormat::EIGHT))
            ->shouldReturn(false);
    }
}