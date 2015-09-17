<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use PhpSpec\ObjectBehavior;
use UCD\Exception\UnexpectedValueException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFiles;

/**
 * @mixin RangeFiles
 */
class RangeFilesSpec extends ObjectBehavior
{
    public function it_is_traversable()
    {
        $this->shouldHaveType(\Traversable::CLASS);
    }

    public function it_is_countable()
    {
        $this->shouldHaveType(\Countable::CLASS);
    }

    public function it_can_have_files_added_to_it(RangeFile $file)
    {
        $this->givenFileHasRange($file, 1, 10);

        $this->add($file)
            ->shouldReturn($file);
    }

    public function it_can_resolve_back_a_file_based_on_a_integer_value_residing_within_a_known_range(RangeFile $file)
    {
        $this->givenFileHasRange($file, 1, 10);

        $this->beConstructedWith([$file]);

        $this->getForValue(5)
            ->shouldReturn($file);
    }

    public function it_returns_NULL_if_a_file_cannot_be_resolved_from_a_supplied_integer_value(RangeFile $file)
    {
        $this->givenFileHasRange($file, 1, 10);

        $this->beConstructedWith([$file]);

        $this->getForValue(11)
            ->shouldReturn(null);
    }

    public function it_throws_UnexpectedValueException_if_more_than_one_file_is_resolved_from_a_supplied_integer_value(
        RangeFile $file1,
        RangeFile $file2
    ) {
        $this->givenFileHasRange($file1, 1, 10);
        $this->givenFileHasRange($file2, 5, 15);

        $this->beConstructedWith([$file1, $file2]);

        $this->shouldThrow(UnexpectedValueException::CLASS)
            ->duringGetForValue(8);
    }

    public function it_can_be_iterated(RangeFile $file1, RangeFile $file2)
    {
        $this->givenFileHasRange($file1, 1, 10);
        $this->givenFileHasRange($file2, 11, 20);

        $this->beConstructedWith([$file1]);
        $this->add($file2);

        $this->shouldIterateLike([$file1, $file2]);
    }

    public function it_exposes_a_count_of_files(RangeFile $file1, RangeFile $file2)
    {
        $this->givenFileHasRange($file1, 1, 10);
        $this->givenFileHasRange($file2, 11, 20);

        $this->beConstructedWith([$file1]);
        $this->add($file2);

        $this->shouldHaveCount(2);
    }

    private function givenFileHasRange(RangeFile $file, $start, $end)
    {
        $file->getRange()
            ->willReturn(new Range($start, $end));
    }
}