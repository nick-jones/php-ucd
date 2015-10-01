<?php

namespace UCD\Application\Console\Command;

use Pimple\Container;
use Symfony\Component\Console\Command\Command;
use UCD\Entity\Character\ReadOnlyRepository;
use UCD\Entity\Character\WritableRepository;

abstract class RepositoryUtilisingCommand extends Command
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        parent::__construct(null);
    }

    /**
     * @param string $name
     * @return ReadOnlyRepository|WritableRepository
     */
    protected function getRepositoryByName($name)
    {
        $key = $this->keyFromName($name);

        if (!isset($this->container[$key])) {
            throw new \InvalidArgumentException(sprintf('No repository with name: %s', $name));
        }

        return $this->container[$key];
    }

    /**
     * @return string[]
     */
    protected function getRepositoryNames()
    {
        $filter = function ($key) {
            return strpos($key, 'repository.') === 0;
        };

        $keys = array_filter($this->container->keys(), $filter);

        return $this->mapContainerKeyNamesToRepositoryNames($keys);
    }

    /**
     * @return string[]
     */
    public function getWritableRepositoryNames()
    {
        $filter = function ($name) {
            return $this->getRepositoryByName($name) instanceof WritableRepository;
        };

        return array_filter($this->getRepositoryNames(), $filter);
    }

    /**
     * @param string[] $keys
     * @return string[]
     */
    private function mapContainerKeyNamesToRepositoryNames(array $keys)
    {
        $mapper = function ($key) {
            return explode('.', $key)[1];
        };

        return array_values(
            array_map($mapper, $keys)
        );
    }

    /**
     * @param string $name
     * @return string
     */
    private function keyFromName($name)
    {
        return sprintf('repository.%s', $name);
    }
}