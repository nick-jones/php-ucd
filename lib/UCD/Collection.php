<?php

namespace UCD;

use UCD\Entity\Character;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Repository;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\CodepointAssigned;
use UCD\Entity\NonCharacter;
use UCD\Entity\Surrogate;

use UCD\Exception\InvalidArgumentException;
use UCD\Exception\OutOfRangeException;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPSerializer;
use UCD\Infrastructure\Repository\CharacterRepository\PHPFileRepository;
use UCD\Infrastructure\Repository\CharacterRepository\TraversableRepository;

class Collection implements \IteratorAggregate
{
    /**
     * @var Repository
     */
    private $sourceRepository;

    /**
     * @param Repository $sourceRepository
     */
    public function __construct(Repository $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    /**
     * @return Collection
     */
    public static function fromFullDatabase()
    {
        return new self(self::defaultRepository());
    }

    /**
     * @param int $codepoint
     * @return CodepointAssigned
     * @throws CharacterNotFoundException
     * @throws InvalidArgumentException
     * @throws OutOfRangeException
     */
    public function getByCodepoint($codepoint)
    {
        $codepoint = Codepoint::fromInt($codepoint);

        return $this->sourceRepository->getByCodepoint($codepoint);
    }

    /**
     * @param int $codepoint
     * @return Character
     * @throws CharacterNotFoundException
     */
    public function getCharacterByCodepoint($codepoint)
    {
        $assigned = $this->getByCodepoint($codepoint);

        if ($assigned instanceof Character) {
            return $assigned;
        }

        throw CharacterNotFoundException::withCodepointValue($codepoint);
    }

    /**
     * @return self
     */
    public function allCharacters()
    {
        return $this->filterWith(function (CodepointAssigned $assigned) {
            return $assigned instanceof Character;
        });
    }

    /**
     * @return self
     */
    public function allNonCharacters()
    {
        return $this->filterWith(function (CodepointAssigned $assigned) {
            return $assigned instanceof NonCharacter;
        });
    }

    /**
     * @return self
     */
    public function allSurrogates()
    {
        return $this->filterWith(function (CodepointAssigned $assigned) {
            return $assigned instanceof Surrogate;
        });
    }

    /**
     * @param callable $filter
     * @return self
     */
    public function filterWith(callable $filter)
    {
        $repository = new TraversableRepository(
            $this->applyFilter($filter)
        );

        return new self($repository);
    }

    /**
     * @param callable $filter
     * @return \Generator
     */
    private function applyFilter(callable $filter)
    {
        foreach ($this as $character) {
            if (call_user_func($filter, $character) === true) {
                yield $character;
            }
        }
    }

    /**
     * @param callable $callback
     * @return self
     */
    public function traverseWith(callable $callback)
    {
        foreach ($this as $character) {
            call_user_func($callback, $character);
        }

        return $this;
    }

    /**
     * @return Repository
     */
    private static function defaultRepository()
    {
        $dbPath = sprintf('%s/../../resources/generated/ucd', __DIR__);
        $dbPathInfo = new \SplFileInfo($dbPath);
        $directory = new PHPFileDirectory($dbPathInfo);
        $serializer = new PHPSerializer();

        return new PHPFileRepository($directory, $serializer);
    }

    /**
     * @return CodepointAssigned[]
     */
    public function getIterator()
    {
        $all = $this->sourceRepository->getAll();

        if ($all instanceof \Traversable) {
            return $all;
        }

        return new \ArrayIterator($all);
    }
}