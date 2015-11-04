<?php

namespace UCD\Console\Application\Command;

use Symfony\Component\Console\Command\Command;

use UCD\Console\Application\Container;
use UCD\Entity\Character\Repository;
use UCD\Entity\Character\WritableRepository;
use UCD\Exception\InvalidArgumentException;

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
     * @return Repository
     * @throws InvalidArgumentException
     */
    protected function getRepositoryByName($name)
    {
        $key = $this->containerKeyFromName($name);

        if (!isset($this->container[$key])) {
            throw new InvalidArgumentException(sprintf('No repository with name: %s', $name));
        }

        return $this->container[$key];
    }

    /**
     * @param string $name
     * @return WritableRepository
     * @throws InvalidArgumentException
     */
    protected function getWritableRepositoryByName($name)
    {
        $repository = $this->getRepositoryByName($name);

        if ($repository instanceof WritableRepository) {
            return $repository;
        }

        throw new InvalidArgumentException(sprintf('No writable repository with name: %s', $name));
    }

    /**
     * @return string[]
     */
    protected function getRepositoryNames()
    {
        $keys = $this->container->idsByPrefix('repository');

        $mapper = function ($key) {
            return explode('.', $key)[1];
        };

        return array_values(
            array_map($mapper, $keys)
        );
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
     * @param string $name
     * @return string
     */
    private function containerKeyFromName($name)
    {
        return sprintf('repository.%s', $name);
    }
}