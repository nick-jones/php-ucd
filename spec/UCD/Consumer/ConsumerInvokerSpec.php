<?php

namespace spec\UCD\Consumer;

use PhpSpec\ObjectBehavior;

use UCD\Consumer\Consumer;
use UCD\Entity\CodepointAssigned;

class ConsumerInvokerSpec extends ObjectBehavior
{
    public function let(Consumer $consumer)
    {
        $this->beConstructedWith($consumer);
    }

    public function it_is_invokable()
    {
        $this->shouldBeInvokable();
    }

    public function it_proxies_invoked_codepoint_assigned_entities_to_the_consumer($consumer, CodepointAssigned $entity)
    {
        $this->__invoke($entity);

        $consumer->consume($entity)
            ->shouldHaveBeenCalled();
    }
}