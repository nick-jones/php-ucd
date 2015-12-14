<?php

namespace UCD;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Collection;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Repository;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Codepoint;
use UCD\Unicode\CodepointAssigned;
use UCD\Unicode\NonCharacter;
use UCD\Unicode\Surrogate;

use UCD\Exception\InvalidArgumentException;
use UCD\Exception\OutOfRangeException;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Serializer\PHPSerializer;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

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
     * @param Block $block
     * @throws Repository\BlockNotFoundException
     * @return Codepoint\Range\Collection
     */
    public function getCodepointsByBlock(Block $block)
    {
        return $this->sourceRepository->getCodepointsByBlock($block);
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return count($this->sourceRepository);
    }

    /**
     * @return Repository
     */
    private static function createFileRepository()
    {
        $dbPath = sprintf('%s/../../resources/generated/ucd', __DIR__);
        $dbPathInfo = new \SplFileInfo($dbPath);
        $charactersDirectory = PHPRangeFileDirectory::fromPath($dbPathInfo);
        $serializer = new PHPSerializer();

        return new FileRepository($charactersDirectory, $serializer);
    }
}