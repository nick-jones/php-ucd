<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use Prophecy\Argument;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Property;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyAggregators;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFileDirectory;
use UCD\Unicode\AggregatorRelay;
use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Character\WritableRepository;
use UCD\Unicode\Codepoint;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Serializer\PHPSerializer;
use UCD\Unicode\Codepoint\Range\Collection;

/**
 * @mixin FileRepository
 */
class FileRepositorySpec extends RepositoryBehaviour
{
    private $serializer;

    public function let(
        RangeFileDirectory $charactersDirectory,
        PropertyFileDirectory $propertiesDirectory,
        PropertyAggregators $aggregators,
        PHPSerializer $serializer
    ) {
        $aggregators->getIterator()
            ->willReturn(new \ArrayIterator());

        $aggregators->addCharacters(Argument::any())
            ->willReturn(null);

        $this->serializer = $serializer;
        $this->beConstructedWith($charactersDirectory, $propertiesDirectory, $aggregators, $serializer);
    }

    public function it_is_writable()
    {
        $this->shouldImplement(WritableRepository::class);
    }

    public function it_can_have_characters_added_to_it(
        $charactersDirectory,
        Character $character
    ) {
        $this->serializer
            ->serialize($character)
            ->willReturn($serialized = ['serialized']);

        $charactersDirectory->writeRange(new Range(0, Codepoint::MAX), [1 => $serialized])
            ->shouldBeCalled();

        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->addMany(Character\Collection::fromArray([
            $character->getWrappedObject()
        ]));
    }

    public function it_notifies_observers_when_characters_are_added(
        \SplObserver $observer,
        Character $character
    ) {
        $observer->update($this)
            ->shouldBeCalled();

        $this->attach($observer);
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->addMany(Character\Collection::fromArray([
            $character->getWrappedObject()
        ]));
    }

    public function it_writes_aggregations_after_all_characters_have_been_added(
        $propertiesDirectory,
        Property $property,
        AggregatorRelay $aggregator,
        $aggregators,
        Character $character
    ) {
        $aggregator->getAllRanges()
            ->willReturn($data = ['foo' => null]);

        $aggregators->getIterator()
            ->will(function () use ($property, $aggregator) {
                yield $property->getWrappedObject() => $aggregator->getWrappedObject();
            });

        $aggregators->addCharacters(Argument::any())
            ->willReturn(null);

        $propertiesDirectory->writeProperty($property, $data)
            ->shouldBeCalled();

        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->addMany(Character\Collection::fromArray([
            $character->getWrappedObject()
        ]));
    }

    public function it_can_retrieve_characters_by_codepoint(
        $charactersDirectory,
        Character $character,
        PHPRangeFile $file
    ) {
        $charactersDirectory->getFileFromValue(1)
            ->willReturn($file);

        $this->givenFileUnserializesTo($file, [1 => $character]);

        $this->getByCodepoint(Codepoint::fromInt(1))
            ->shouldReturn($character);
    }

    public function it_should_throw_CharacterNotFoundException_if_the_requested_character_is_not_found(
        $charactersDirectory,
        PHPRangeFile $file,
        Character $character
    ) {
        $charactersDirectory->getFileFromValue(1)
            ->willReturn($file);

        $this->givenFileUnserializesTo($file, [0 => $character]);

        $this->shouldThrow(CharacterNotFoundException::class)
            ->duringGetByCodePoint(Codepoint::fromInt(1));
    }

    public function it_exposes_all_available_characters(
        $charactersDirectory,
        PHPRangeFile $file1,
        PHPRangeFile $file2,
        Character $character1,
        Character $character2
    ) {
        $charactersDirectory->getFiles()
            ->willReturn(new \ArrayIterator([$file1->getWrappedObject(), $file2->getWrappedObject()]));

        $this->givenFileUnserializesTo($file1, [1 => $character1]);
        $this->givenFileUnserializesTo($file2, [5 => $character2]);

        $this->getAll()
            ->shouldIterateLike([1 => $character1, 5 => $character2]);
    }

    public function it_exposes_nothing_if_no_characters_are_available($charactersDirectory)
    {
        $charactersDirectory->getFiles()
            ->willReturn(new \ArrayIterator([]));

        $this->getAll()
            ->shouldIterateLike([]);
    }

    public function it_exposes_codepoints_for_a_requested_block(
        $propertiesDirectory,
        PropertyFile $file,
        Collection $codepoints
    ) {
        $propertiesDirectory->getFileForProperty(Property::ofType(Property::BLOCK))
            ->willReturn($file);

        $file->read()
            ->willReturn([Block::AEGEAN_NUMBERS => 's:{}']);

        $this->serializer->unserialize('s:{}')
            ->willReturn($codepoints);

        $this->getCodepointsByBlock(Block::fromValue(Block::AEGEAN_NUMBERS))
            ->shouldReturn($codepoints);
    }

    public function it_exposes_the_number_of_characters_available(
        $charactersDirectory,
        PHPRangeFile $file1,
        PHPRangeFile $file2
    ) {
        $charactersDirectory->getFiles()
            ->willReturn(new \ArrayIterator([$file1->getWrappedObject(), $file2->getWrappedObject()]));

        $file1->getTotal()
            ->willReturn(1);

        $file2->getTotal()
            ->willReturn(2);

        $this->count()
            ->shouldReturn(3);
    }

    private function givenFileUnserializesTo(PHPRangeFile $file, array $characters)
    {
        $values = [];

        foreach ($characters as $i => $character) {
            $serialized = sprintf('serialized:%d', $i);
            $values[$i] = $serialized;

            $this->serializer
                ->unserialize($serialized)
                ->willReturn($character);
        }

        $file->read()
            ->willReturn($values);
    }
}