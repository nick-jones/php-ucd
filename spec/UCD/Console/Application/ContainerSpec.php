<?php

namespace spec\UCD\Console\Application;

use PhpSpec\ObjectBehavior;
use UCD\Console\Application\Container;

/**
 * @mixin Container
 */
class ContainerSpec extends ObjectBehavior
{
    public function it_can_retrieve_service_ids_by_prefix()
    {
        $this->offsetSet('foo.1', 'x');
        $this->offsetSet('foo.2', 'x');
        $this->offsetSet('foo.3', 'x');
        $this->offsetUnset('foo.3');
        $this->offsetSet('bar.4', 'x');

        $this->idsByPrefix('foo')
            ->shouldReturn(['foo.1', 'foo.2']);
    }
}