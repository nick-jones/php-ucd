<?php

namespace spec\UCD\Unicode;

use PhpSpec\ObjectBehavior;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Comparable;
use UCD\Exception\InvalidArgumentException;
use UCD\Exception\OutOfRangeException;

/**
 * @mixin Codepoint
 */
class CodepointSpec extends ObjectBehavior
{
    public function it_should_throw_an_OutOfRangeException_if_the_codepoint_less_than_zero()
    {
        $this->beConstructedThrough('fromInt', [-1]);
        $this->shouldThrow(OutOfRangeException::class);
    }

    public function it_should_throw_an_OutOfRangeException_if_the_codepoint_greater_than_0x10FFFF()
    {
        $this->beConstructedThrough('fromInt', [0x110000]);
        $this->shouldThrow(OutOfRangeException::class);
    }

    public function it_should_throw_an_InvalidArgumentException_if_value_is_a_non_integer()
    {
        $this->beConstructedThrough('fromInt', [0x110000]);
        $this->shouldThrow(InvalidArgumentException::class);
    }

    public function it_should_expose_its_codepoint_value_as_an_integer()
    {
        $this->beConstructedThrough('fromInt', [0x10]);

        $this->getValue()
            ->shouldReturn(0x10);
    }

    public function it_can_be_created_from_a_hex_string()
    {
        $this->beConstructedThrough('fromHex', ['10']);

        $this->getValue()
            ->shouldReturn(0x10);
    }

    public function it_can_be_created_from_a_UTF8_encoded_character()
    {
        $this->beConstructedThrough('fromUTF8', ['£']);

        $this->getValue()
            ->shouldReturn(0xA3);
    }

    public function it_throws_if_the_provided_UTF8_string_is_not_exactly_one_character()
    {
        $this->beConstructedThrough('fromUTF8', ['£$']);

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_can_be_created_from_a_UTF16BE_encoded_character()
    {
        $this->beConstructedThrough('fromUTF16BE', ["\xD8\x01\xDC\x37"]); // D801 (high) DC37 (low)

        $this->getValue()
            ->shouldReturn(0x10437);
    }

    public function it_throws_if_the_provided_UTF16BE_string_is_not_exactly_one_character()
    {
        $this->beConstructedThrough('fromUTF16BE', ["\xD8\x01\xDC\x37\x00\x24"]);

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_can_be_created_from_a_UTF16LE_encoded_character()
    {
        $this->beConstructedThrough('fromUTF16LE', ["\x01\xD8\x37\xDC"]); // D801 (high) DC37 (low)

        $this->getValue()
            ->shouldReturn(0x10437);
    }

    public function it_throws_if_the_provided_UTF16LE_string_is_not_exactly_one_character()
    {
        $this->beConstructedThrough('fromUTF16LE', ["\x01\xD8\x37\xDC\x24\x00"]);

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_can_be_created_from_a_UTF32BE_encoded_character()
    {
        $this->beConstructedThrough('fromUTF32BE', ["\x00\x01\x04\x37"]);

        $this->getValue()
            ->shouldReturn(0x10437);
    }

    public function it_throws_if_the_provided_UTF32BE_string_is_not_exactly_one_character()
    {
        $this->beConstructedThrough('fromUTF32BE', ["\x00\x01\x04\x37\x00\x00\x00\x24"]);

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_can_be_created_from_a_UTF32LE_encoded_character()
    {
        $this->beConstructedThrough('fromUTF32LE', ["\x37\x04\x01\x00"]);

        $this->getValue()
            ->shouldReturn(0x10437);
    }

    public function it_throws_if_the_provided_UTF32LE_string_is_not_exactly_one_character()
    {
        $this->beConstructedThrough('fromUTF32LE', ["\x37\x04\x01\x00\x24\x00\x00\x00"]);

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_can_provide_a_UTF8_representation_of_its_value()
    {
        $this->beConstructedThrough('fromInt', [0x1F377]);

        $this->toUTF8()
            ->shouldReturn("\xF0\x9F\x8D\xB7");
    }

    public function it_can_provide_a_UTF16BE_representation_of_its_value()
    {
        $this->beConstructedThrough('fromInt', [0x1F377]);

        $this->toUTF16BE()
            ->shouldReturn("\xD8\x3C\xDF\x77");
    }

    public function it_can_provide_a_UTF16LE_representation_of_its_value()
    {
        $this->beConstructedThrough('fromInt', [0x1F377]);

        $this->toUTF16LE()
            ->shouldReturn("\x3C\xD8\x77\xDF");
    }

    public function it_can_provide_a_UTF32BE_representation_of_its_value()
    {
        $this->beConstructedThrough('fromInt', [0x1F377]);

        $this->toUTF32BE()
            ->shouldReturn("\x00\x01\xF3\x77");
    }

    public function it_can_provide_a_UTF32LE_representation_of_its_value()
    {
        $this->beConstructedThrough('fromInt', [0x1F377]);

        $this->toUTF32LE()
            ->shouldReturn("\x77\xF3\x01\x00");
    }

    public function it_can_provide_a_PHP_unicode_escape_sequence_representation_of_its_value()
    {
        $this->beConstructedThrough('fromInt', [0x1F377]);

        $this->toUnicodeEscape()
            ->shouldReturn('\u{1F377}');
    }

    public function it_should_be_comparable()
    {
        $this->beConstructedThrough('fromInt', [0x10]);
        $this->shouldImplement(Comparable::class);
    }

    public function it_should_equal_an_instance_with_the_same_value()
    {
        $this->beConstructedThrough('fromInt', [0x10]);

        $this->equals(Codepoint::fromInt(0x10))
            ->shouldReturn(true);
    }

    public function it_should_not_equal_an_instance_with_a_different_value()
    {
        $this->beConstructedThrough('fromInt', [0x11]);

        $this->equals(Codepoint::fromInt(0x10))
            ->shouldReturn(false);
    }

    public function it_should_be_possible_to_cast_it_to_a_string()
    {
        $this->beConstructedThrough('fromInt', [0xA]);

        $this->__toString()
            ->shouldReturn('U+A');
    }
}