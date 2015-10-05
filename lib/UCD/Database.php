<?php

namespace UCD;

use UCD\Entity\Character;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Repository;
use UCD\Entity\Character\Repository\CharacterNotFoundException;

use UCD\Entity\CodepointAssigned;
use UCD\Exception\InvalidArgumentException;
use UCD\Exception\OutOfRangeException;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPSerializer;
use UCD\Infrastructure\Repository\CharacterRepository\PHPFileRepository;

class Database
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @param Repository|null $repository
     */
    public function __construct(Repository $repository = null)
    {
        $this->repository = $repository ?: $this->defaultRepository();
    }

    /**
     * @param int $codepoint
     * @return CodepointAssigned
     * @throws CharacterNotFoundException
     * @throws InvalidArgumentException
     * @throws OutOfRangeException
     */
    public function locate($codepoint)
    {
        $codepoint = Codepoint::fromInt($codepoint);

        return $this->repository->getByCodepoint($codepoint);
    }

    /**
     * @param int $codepoint
     * @return Character
     * @throws CharacterNotFoundException
     */
    public function locateCharacter($codepoint)
    {
        $codepoint = Codepoint::fromInt($codepoint);
        $assigned = $this->repository->getByCodepoint($codepoint);

        if ($assigned instanceof Character) {
            return $assigned;
        }

        throw CharacterNotFoundException::withCodepoint($codepoint);
    }

    /**
     * @return CodepointAssigned[]
     */
    public function all()
    {
        return $this->repository->getAll();
    }

    /**
     * @return Character[]
     */
    public function allCharacters()
    {
        return $this->filter(function (CodepointAssigned $assigned) {
            return $assigned instanceof Character;
        });
    }

    /**
     * @param callable $filter
     * @return CodepointAssigned[]
     */
    public function filter(callable $filter)
    {
        return $this->filterWith($this->all(), $filter);
    }

    /**
     * @param callable $filter
     * @return Character[]
     */
    public function filterCharacters(callable $filter)
    {
        return $this->filterWith($this->allCharacters(), $filter);
    }

    /**
     * @param CodepointAssigned[]|\Traversable $characters
     * @param callable $filter
     * @return CodepointAssigned[]|\Generator
     */
    private function filterWith($characters, callable $filter)
    {
        foreach ($characters as $character) {
            if (call_user_func($filter, $character) === true) {
                yield $character;
            }
        }
    }

    /**
     * @param callable $callback
     * @return bool
     */
    public function walk(callable $callback)
    {
        return $this->walkWith($this->all(), $callback);
    }

    /**
     * @param callable $callback
     * @return bool
     */
    public function walkCharacters(callable $callback)
    {
        return $this->walkWith($this->allCharacters(), $callback);
    }

    /**
     * @param \Traversable|CodepointAssigned[] $characters
     * @param callable $callback
     * @return bool
     */
    private function walkWith($characters, callable $callback)
    {
        foreach ($characters as $character) {
            call_user_func($callback, $character);
        }

        return true;
    }

    /**
     * @return Repository
     */
    private function defaultRepository()
    {
        $dbPath = sprintf('%s/../../resources/generated/db', __DIR__);
        $dbPathInfo = new \SplFileInfo($dbPath);
        $directory = new PHPFileDirectory($dbPathInfo);
        $serializer = new PHPSerializer();

        return new PHPFileRepository($directory, $serializer);
    }
}