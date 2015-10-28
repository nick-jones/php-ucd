<?php

namespace spec\UCD\Entity\Character;

use PhpSpec\ObjectBehavior;

use PhpSpec\Wrapper\Collaborator;
use UCD\Consumer\Consumer;
use UCD\Entity\Character\Collection;
use UCD\Entity\CodepointAssigned;

/**
 * @mixin Collection
 */
class CollectionSpec extends ObjectBehavior
{
    public function it_should_be_traversable()
    {
        $this->givenTheCollectionContains([]);
        $this->shouldImplement(\Traversable::class);
    }

    public function it_can_be_filtered_using_custom_filter_rules(CodepointAssigned $c1, CodepointAssigned $c2)
    {
        $filter = function () {
            static $i = 0;
            return $i++ === 0;
        };

        $this->givenTheCollectionContains([$c1, $c2]);

        $this->filterWith($filter)
            ->shouldIterateLike([$c1]);
    }

    public function it_can_be_traversed_by_providing_a_callback(
        CodepointAssigned $character
    ) {
        // TODO: use a prediction on an invokable class once phpspec __invoke fix is tagged.

        $this->givenTheCollectionContains([$character]);
        $count = 0;

        $callback = function (CodepointAssigned $c) use (&$count) {
            ++$count;
        };

        $this->traverseWith($callback);

        if ($count !== 1) {
            throw new \RuntimeException();
        }
    }

    public function it_can_be_traversed_by_providing_a_consumer(
        Consumer $consumer,
        CodepointAssigned $character
    ) {
        $this->givenTheCollectionContains([$character]);
        $this->traverseWithConsumer($consumer);

        $consumer->consume($character)
            ->shouldHaveBeenCalled();
    }

    private function givenTheCollectionContains(array $items)
    {
        $unwrapped = array_map(function (Collaborator $c) {
            return $c->getWrappedObject();
        }, $items);

        $this->beConstructedWith(new \ArrayIterator($unwrapped));
    }
}