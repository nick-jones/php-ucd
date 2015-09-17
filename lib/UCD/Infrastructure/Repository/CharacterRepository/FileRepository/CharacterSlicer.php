<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use UCD\Entity\Character;
use UCD\Entity\Character\Codepoint;

class CharacterSlicer
{
    /**
     * @param Character[]|\Traversable $characters
     * @param int $size
     * @return Character[]|\Generator
     */
    public static function slice($characters, $size)
    {
        $start = Codepoint::MIN;
        $tally = 0;
        $chunk = [];

        foreach ($characters as $character) {
            array_push($chunk, $character);
            $current = $character->getCodepointValue();

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