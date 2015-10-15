<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;

class CharacterSlicer
{
    /**
     * @param CodepointAssigned[] $characters
     * @param int $size
     * @return CodepointAssigned[]
     */
    public static function slice($characters, $size)
    {
        $start = Codepoint::MIN;
        $tally = 0;
        $chunk = [];

        foreach ($characters as $character) {
            array_push($chunk, $character);
            $codepoint = $character->getCodepoint();
            $current = $codepoint->getValue();

            if ((++$tally % $size) === 0) {
                yield (new Range($start, $current)) => $chunk;
                $chunk = [];
                $start = ++$current;
            }
        }

        if (count($chunk) > 0) {
            yield (new Range($start, Codepoint::MAX)) => $chunk;
        }
    }
}