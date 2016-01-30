<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use Prophecy\Argument;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Repository\BlockNotFoundException;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Character\Properties;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CodepointAssignedParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CodepointCountParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\CodepointElementReader;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;
use UCD\Unicode\Codepoint\Range;

/**
 * @mixin XMLRepository
 */
class XMLRepositorySpec extends RepositoryBehaviour
{
    private $reader;
    private $parser;
    private $countParser;

    public function let(CodepointElementReader $reader, CodepointAssignedParser $parser, CodepointCountParser $countParser)
    {
        $this->reader = $reader;
        $this->parser = $parser;
        $this->countParser = $countParser;

        $this->beConstructedWith($reader, $parser, $countParser);
    }

    public function it_can_retrieve_characters_by_codepoint(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTheXMLParsesTo([$character]);

        $this->getByCodepoint(Codepoint::fromInt(1))
            ->shouldReturn($character);
    }

    public function it_can_retrieve_characters_by_codepoints(Character $character1, Character $character2)
    {
        $this->givenCharacterHasCodepointWithValue($character1, 1);
        $this->givenCharacterHasCodepointWithValue($character2, 2);
        $this->givenTheXMLParsesTo([$character1, $character2]);

        $this->getByCodepoints(Codepoint\Collection::fromArray([Codepoint::fromInt(1)]))
            ->shouldIterateLike([$character1]);
    }

    public function it_should_throw_CharacterNotFoundException_if_the_requested_character_is_not_found()
    {
        $this->givenTheXMLParsesTo([]);

        $this->shouldThrow(CharacterNotFoundException::class)
            ->duringGetByCodePoint(Codepoint::fromInt(1));
    }

    public function it_exposes_all_available_characters(Character $character)
    {
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->givenTheXMLParsesTo([$character]);

        $this->getAll()
            ->shouldIterateLike([1 => $character]);
    }

    public function it_exposes_nothing_if_no_characters_are_available($reader)
    {
        $reader->read()
            ->willReturn([]);

        $this->getAll()
            ->shouldIterateLike([]);
    }

    public function it_exposes_codepoints_for_a_requested_block(Character $c1, Character $c2, Character $c3)
    {
        $this->givenCharacterHasCodepointWithValue($c1, 1);
        $this->givenCharacterHasCodepointWithValue($c2, 2);
        $this->givenCharacterHasCodepointWithValue($c3, 3);

        $this->givenCharacterResidesInBlock($c1, Block::fromValue(Block::AEGEAN_NUMBERS));
        $this->givenCharacterResidesInBlock($c2, Block::fromValue(Block::AEGEAN_NUMBERS));
        $this->givenCharacterResidesInBlock($c3, Block::fromValue(Block::AHOM));

        $this->givenTheXMLParsesTo([$c1, $c2, $c3]);

        $this->getCodepointsByBlock(Block::fromValue(Block::AEGEAN_NUMBERS))
            ->shouldIterateLike([Range::between(Codepoint::fromInt(1), Codepoint::fromInt(2))]);
    }

    public function it_throws_BlockNotFoundException_if_a_supplied_block_is_not_known($reader)
    {
        $reader->read()
            ->willReturn([]);

        $this->shouldThrow(BlockNotFoundException::class)
            ->during('getCodepointsByBlock', [Block::fromValue(Block::ALCHEMICAL_SYMBOLS)]);
    }

    public function it_exposes_the_number_of_characters_available()
    {
        $element = new \DOMElement('char');
        (new \DOMDocument())->appendChild($element);

        $this->reader
            ->read()
            ->willReturn([$element]);

        $this->countParser
            ->parseElement($element)
            ->willReturn(5);

        $this->count()
            ->shouldReturn(5);
    }

    /**
     * @param array $characters
     */
    private function givenTheXMLParsesTo(array $characters)
    {
        $element = new \DOMElement('char');
        (new \DOMDocument())->appendChild($element);

        $this->reader
            ->read()
            ->willReturn([$element]);

        $this->parser
            ->parseElement($element)
            ->willReturn($characters);
    }
}