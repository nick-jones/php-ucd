<?php

namespace UCD\Console\Application\Container;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

abstract class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @param array $services
     */
    protected function addMany(Container $container, array $services)
    {
        foreach ($services as $id => $definition) {
            $container[$id] = $definition;
        }
    }
}