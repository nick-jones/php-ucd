<?php

namespace UCD\Entity\Character;

use UCD\Consumer\CodepointAggregatingConsumer;
use UCD\Consumer\Consumer;
use UCD\Consumer\ConsumerInvoker;
use UCD\Consumer\RegexBuildingConsumer;

use UCD\Entity\Codepoint;
use UCD\Entity\Codepoint\Range;
use UCD\Entity\CodepointAssigned;
use UCD\Entity\Collection\TraversableBackedCollection;

class Collection extends TraversableBackedCollection
{
    /**
     * @param Consumer $consumer
     * @return $this
     */
    public function traverseWithConsumer(Consumer $consumer)
    {
        return $this->traverseWith(
            new ConsumerInvoker($consumer)
        );
    }

    /**
     * @return Codepoint\Collection|Codepoint[]
     */
    public function asCodepoints()
    {
        return new Codepoint\Collection(
            $this->yieldCodepoints()
        );
    }

    /**
     * @return \Generator
     */
    private function yieldCodepoints()
    {
        /** @var CodepointAssigned $character */
        foreach ($this as $character) {
            yield $character->getCodepoint();
        }
    }

    /**
     * @return Range\Collection|Range[]
     */
    public function asCodepointRanges()
    {
        $consumer = new CodepointAggregatingConsumer();
        $this->traverseWithConsumer($consumer);

        return $consumer->getAggregated();
    }

    /**
     * @return string
     */
    public function asRegexCharacterClass()
    {
        $aggregator = new CodepointAggregatingConsumer();
        $consumer = new RegexBuildingConsumer($aggregator);
        $this->traverseWithConsumer($consumer);

        return $consumer->getCharacterClass();
    }

    /**
     * @param array $items
     * @return Collection
     */
    public static function fromArray(array $items)
    {
        return new self(
            new \ArrayIterator($items)
        );
    }
}