<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use PhpSpec\ObjectBehavior;
use UCD\Entity\Character;
use UCD\Entity\Codepoint;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\CharacterSlicer;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;

/**
 * @mixin CharacterSlicer
 */
class CharacterSlicerSpec extends ObjectBehavior
{
    public function it_should_return_chunks_matching_the_slice_size(
        Character $c1,
        Character $c2,
        Character $c3,
        Character $c4,
        Character $c5
    ) {
        $characters = [$c1, $c2, $c3, $c4, $c5];

        foreach ($characters as $i => $character) {
            $this->givenCharacterHasCodepointWithValue($character, $i+1);
        }

        $slices = $this->slice($characters, 2);
        $slices->shouldHaveKeys([new Range(0, 2), new Range(3, 4), new Range(5, 0x10FFFF)]);

        $slices = $this->slice($characters, 2);
        $slices->shouldHaveValues([[$c1, $c2], [$c3, $c4], [$c5]]);
    }

    private function givenCharacterHasCodepointWithValue(Character $character, $value)
    {
        $character->getCodepoint()
            ->willReturn(Codepoint::fromInt($value));

        $character->getCodepointValue()
            ->willReturn($value);
    }

    public function getMatchers()
    {
        return [
            'haveKeys' => function ($subject, $expectedRanges) {
                /** @var Range $range */
                foreach ($subject as $range => $characters) {
                    /** @var Range $expected */
                    $expected = array_shift($expectedRanges);
                    if ($range->getStart() !== $expected->getStart() || $range->getEnd() !== $expected->getEnd()) {
                        return false;
                    }
                }
                return count($expectedRanges) === 0;
            },
            'haveValues' => function ($subject, $expected) {
                return $expected == iterator_to_array($subject, false);
            }
        ];
    }
}