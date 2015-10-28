<?php

namespace UCD;

use UCD\Entity\Character;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Repository;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\CodepointAssigned;
use UCD\Entity\Character\Collection;
use UCD\Entity\NonCharacter;
use UCD\Entity\Surrogate;

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
    private $sourceRepository;

    /**
     * @param Repository $sourceRepository
     */
    public function __construct(Repository $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    /**
     * @return static
     */
    public static function fromDisk()
    {
        return new static(
            self::createFileRepository()
        );
    }

    /**
     * @param Codepoint $codepoint
     * @return CodepointAssigned
     * @throws CharacterNotFoundException
     * @throws InvalidArgumentException
     * @throws OutOfRangeException
     */
    public function getByCodepoint(Codepoint $codepoint)
    {
        return $this->sourceRepository->getByCodepoint($codepoint);
    }

    /**
     * @param Codepoint $codepoint
     * @return Character
     * @throws CharacterNotFoundException
     */
    public function getCharacterByCodepoint(Codepoint $codepoint)
    {
        $assigned = $this->getByCodepoint($codepoint);

        if ($assigned instanceof Character) {
            return $assigned;
        }

        throw CharacterNotFoundException::withCodepoint($codepoint);
    }

    /**
     * @return Collection
     */
    public function all()
    {
        return $this->sourceRepository->getAll();
    }

    /**
     * @return Collection
     */
    public function onlyCharacters()
    {
        return $this->filterWith(function (CodepointAssigned $assigned) {
            return $assigned instanceof Character;
        });
    }

    /**
     * @return Collection
     */
    public function onlyNonCharacters()
    {
        return $this->filterWith(function (CodepointAssigned $assigned) {
            return $assigned instanceof NonCharacter;
        });
    }

    /**
     * @return Collection
     */
    public function onlySurrogates()
    {
        return $this->filterWith(function (CodepointAssigned $assigned) {
            return $assigned instanceof Surrogate;
        });
    }

    /**
     * @param callable $filter
     * @return Collection
     */
    private function filterWith(callable $filter)
    {
        return $this->all()
            ->filterWith($filter);
    }

    /**
     * @return Repository
     */
    private static function createFileRepository()
    {
        $dbPath = sprintf('%s/../../resources/generated/ucd', __DIR__);
        $dbPathInfo = new \SplFileInfo($dbPath);
        $directory = new PHPFileDirectory($dbPathInfo);
        $serializer = new PHPSerializer();

        return new PHPFileRepository($directory, $serializer);
    }
}