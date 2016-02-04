<?php

namespace UCD\Unicode\Codepoint;

use UCD\Unicode\Codepoint;
use UCD\Unicode\Codepoint\Range\RangeRegexBuilder;
use UCD\Unicode\Collection\TraversableBackedCollection;

class Collection extends TraversableBackedCollection
{
    /**
     * @return \Traversable|int[]
     */
    public function flatten()
    {
        /** @var Codepoint $codepoint */
        foreach ($this as $codepoint) {
            yield $codepoint->getValue();
        }
    }

    /**
     * @param Codepoint $codepoint
     * @return bool
     */
    public function has(Codepoint $codepoint)
    {
        foreach ($this as $check) {
            if ($codepoint->equals($check)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Range[]|Range\Collection
     */
    public function aggregate()
    {
        $aggregator = new Aggregator();

        $this->traverseWith(function (Codepoint $codepoint) use ($aggregator) {
            $aggregator->addCodepoint($codepoint);
        });

        return $aggregator->getAggregated();
    }

    /**
     * @return string
     */
    public function toRegexCharacterClass()
    {
        $builder = new RegexBuilder();

        $this->traverseWith(function (Codepoint $codepoint) use ($builder) {
            $builder->addCodepoint($codepoint);
        });

        return $builder->getCharacterClass();
    }

    /**
     * @param string $string
     * @return static
     */
    public static function fromUTF8($string)
    {
        static $encoding = 'UTF-8';
        $codepoints = [];

        for ($i = 0; $i < mb_strlen($string, $encoding); $i++) {
            $character = mb_substr($string, $i, 1, $encoding);
            $codepoint = Codepoint::fromUTF8($character);
            array_push($codepoints, $codepoint);
        }

        return static::fromArray($codepoints);
    }
}