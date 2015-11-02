<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use UCD\Entity\Character;
use UCD\Entity\Character\Properties;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Character\WritableRepository;
use UCD\Entity\Codepoint;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Serializer\PHPSerializer;

/**
 * @mixin FileRepository
 */
class FileRepositorySpec extends ObjectBehavior
{
    private $serializer;

    public function let(RangeFileDirectory $charactersDirectory, PHPSerializer $serializer)
    {
        $this->serializer = $serializer;
        $this->beConstructedWith($charactersDirectory, $serializer);
    }

    public function it_is_writable()
    {
        $this->shouldImplement(WritableRepository::class);
    }

    public function it_can_have_characters_added_to_it($charactersDirectory, Character $character, PHPRangeFile $file)
    {
        $charactersDirectory->addFileFromRangeAndTotal(new Range(0, Codepoint::MAX), 1)
            ->willReturn($file);

        $this->serializer
            ->serialize($character)
            ->willReturn($serialized = ['serialized']);

        $file->write([1 => $serialized])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->addMany(Character\Collection::fromArray([
            $character->getWrappedObject()
        ]));
    }

    public function it_notifies_observers_when_characters_are_added(
        $charactersDirectory,
        \SplObserver $observer,
        Character $character,
        PHPRangeFile $file
    ) {
        $charactersDirectory->addFileFromRangeAndTotal(Argument::cetera())
            ->willReturn($file);

        $observer->update($this)
            ->shouldBeCalled();

        $this->attach($observer);
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

        $this->givenFileUnserializesTo($file, 1, $character);

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

        $this->givenFileUnserializesTo($file, 0, $character);

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

        $this->givenFileUnserializesTo($file1, 1, $character1);
        $this->givenFileUnserializesTo($file2, 5, $character2);

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

    private function givenFileUnserializesTo(PHPRangeFile $file, $offset, Character $character)
    {
        $serialized = sprintf('serialized:%d', $offset);

        $file->read()
            ->willReturn([$offset => $serialized]);

        $this->serializer
            ->unserialize($serialized)
            ->willReturn($character);
    }

    private function givenCharacterHasCodepointWithValue(Character $character, $value)
    {
        $character->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));

        $character->getCodepointValue()
            ->willReturn($value);
    }
}