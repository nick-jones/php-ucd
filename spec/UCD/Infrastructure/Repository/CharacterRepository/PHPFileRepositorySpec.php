<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use UCD\Entity\Character;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Properties;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Character\WritableRepository;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPSerializer;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\PHPFileRepository;

/**
 * @mixin PHPFileRepository
 */
class PHPFileRepositorySpec extends ObjectBehavior
{
    private $serializer;

    public function let(PHPFileDirectory $dir, PHPSerializer $serializer)
    {
        $this->serializer = $serializer;
        $this->beConstructedWith($dir, $serializer);
    }

    public function it_is_writable()
    {
        $this->shouldHaveType(WritableRepository::class);
    }

    public function it_can_have_characters_added_to_it($dir, Character $character, PHPRangeFile $file)
    {
        $dir->createFileFromDetails(new Range(0, Codepoint::MAX), 1)
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
        $dir,
        \SplObserver $observer,
        Character $character,
        PHPRangeFile $file
    ) {
        $dir->createFileFromDetails(Argument::cetera())
            ->willReturn($file);

        $observer->update($this)
            ->shouldBeCalled();

        $this->attach($observer);
        $this->givenCharacterHasCodepointWithValue($character, 1);
        $this->addMany(Character\Collection::fromArray([
            $character->getWrappedObject()
        ]));
    }

    public function it_can_retrieve_characters_by_codepoint($dir, Character $character, PHPRangeFile $file)
    {
        $dir->getFileFromValue(1)
            ->willReturn($file);

        $this->givenFileUnserializesTo($file, 1, $character);

        $this->getByCodepoint(Codepoint::fromInt(1))
            ->shouldReturn($character);
    }

    public function it_should_throw_CharacterNotFoundException_if_the_requested_character_is_not_found(
        $dir,
        PHPRangeFile $file,
        Character $character
    ) {
        $dir->getFileFromValue(1)
            ->willReturn($file);

        $this->givenFileUnserializesTo($file, 0, $character);

        $this->shouldThrow(CharacterNotFoundException::class)
            ->duringGetByCodePoint(Codepoint::fromInt(1));
    }

    public function it_exposes_all_available_characters(
        $dir,
        PHPRangeFile $file1,
        PHPRangeFile $file2,
        Character $character1,
        Character $character2
    ) {
        $dir->getIterator()
            ->willReturn(new \ArrayIterator([$file1->getWrappedObject(), $file2->getWrappedObject()]));

        $this->givenFileUnserializesTo($file1, 1, $character1);
        $this->givenFileUnserializesTo($file2, 5, $character2);

        $this->getAll()
            ->shouldIterateLike([1 => $character1, 5 => $character2]);
    }

    public function it_exposes_nothing_if_no_characters_are_available($dir)
    {
        $dir->getIterator()
            ->willReturn(new \ArrayIterator([]));

        $this->getAll()
            ->shouldIterateLike([]);
    }

    public function it_exposes_the_number_of_characters_available($dir, PHPRangeFile $file1, PHPRangeFile $file2)
    {
        $dir->getIterator()
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