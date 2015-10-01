<?php

namespace UCD;

use UCD\Entity\Character;
use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\ReadOnlyRepository;
use UCD\Entity\Character\Repository\CharacterNotFoundException;

use UCD\Exception\InvalidArgumentException;
use UCD\Exception\OutOfRangeException;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPSerializer;
use UCD\Infrastructure\Repository\CharacterRepository\PHPFileRepository;

class UCD
{
    /**
     * @var ReadOnlyRepository
     */
    private $repository;

    /**
     * @param ReadOnlyRepository|null $repository
     */
    public function __construct(ReadOnlyRepository $repository = null)
    {
        $this->repository = $repository ?: $this->defaultRepository();
    }

    /**
     * @param int $codepoint
     * @return Character
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
     * @return Character[]
     */
    public function all()
    {
        return $this->repository->getAll();
    }

    /**
     * @param callable $callback
     * @return Character[]
     */
    public function filter(callable $callback)
    {
        foreach ($this->all() as $character) {
            if (call_user_func($callback, $character) === true) {
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
        foreach ($this->all() as $character) {
            call_user_func($callback, $character);
        }

        return true;
    }

    /**
     * @return ReadOnlyRepository
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