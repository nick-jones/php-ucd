<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use UCD\Entity\Character;
use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\Properties;
use UCD\Entity\Character\Repository\CharacterNotFoundException;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementReader;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

/**
 * @mixin XMLRepository
 */
class XMLRepositorySpec extends ObjectBehavior
{
    private $reader;
    private $parser;

    public function let(ElementReader $reader, ElementParser $parser)
    {
        $this->reader = $reader;
        $this->parser = $parser;

        $this->beConstructedWith($reader, $parser);
    }

    public function it_can_retrieve_characters_by_codepoint(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTheXMLParsesTo($character);

        $this->getByCodepoint(Codepoint::fromInt(1))
            ->shouldReturn($character);
    }

    public function it_should_throw_CharacterNotFoundException_if_the_requested_character_is_not_found($reader)
    {
        $reader->read()
            ->willReturn([]);

        $this->shouldThrow(CharacterNotFoundException::CLASS)
            ->duringGetByCodePoint(Codepoint::fromInt(1));
    }

    public function it_exposes_all_available_characters(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTheXMLParsesTo($character);

        $this->getAll()
            ->shouldIterateLike([1 => $character]);
    }

    public function it_exposes_an_empty_array_if_no_characters_are_available($reader)
    {
        $reader->read()
            ->willReturn([]);

        $this->getAll()
            ->shouldIterateLike([]);
    }

    public function it_exposes_the_number_of_characters_available(Character $character)
    {
        $this->givenTheXMLParsesTo($character);

        $this->count()
            ->shouldReturn(1);
    }

    private function givenCharacterHasCodepointWithValue(Character $character, $value)
    {
        $character->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));

        $character->getCodepointValue()
            ->willReturn($value);
    }

    /**
     * @param Character $character
     */
    private function givenTheXMLParsesTo(Character $character)
    {
        $element = new \DOMElement('char');
        (new \DOMDocument())->appendChild($element);

        $this->reader
            ->read()
            ->willReturn([$element]);

        $this->parser
            ->parseElement($element)
            ->willReturn([$character]);
    }
}