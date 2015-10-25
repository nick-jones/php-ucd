<?php

namespace UCD\Consumer;

use UCD\Entity\CodepointAssigned;

class ConsumerInvoker
{
    /**
     * @var Consumer
     */
    private $consumer;

    /**
     * @param Consumer $consumer
     */
    public function __construct(Consumer $consumer)
    {
        $this->consumer = $consumer;
    }

    /**
     * @param CodepointAssigned $entity
     */
    public function __invoke(CodepointAssigned $entity)
    {
        $this->consumer->consume($entity);
    }
}