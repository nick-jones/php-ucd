<?php

namespace UCD;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Collection;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Character\Properties\General\Script;
use UCD\Unicode\Character\Repository;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Codepoint\AggregatorRelay;
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
        return $this->sourceRepository
            ->getByCodepoint($codepoint);
    }

    /**
     * @param Codepoint\Collection $codepoints
     * @return Character\Collection|CodepointAssigned[]
     */
    public function getByCodepoints(Codepoint\Collection $codepoints)
    {
        return $this->sourceRepository
            ->getByCodepoints($codepoints);
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
     * @return Collection|CodepointAssigned[]
     */
    public function all()
    {
        return $this->sourceRepository
            ->getAll();
    }

    /**
     * @return Collection|Character[]
     */
    public function onlyCharacters()
    {
        return $this->all()
            ->getCharacters();
    }

    /**
     * @return Collection|NonCharacter[]
     */
    public function onlyNonCharacters()
    {
        return $this->all()
            ->getNonCharacters();
    }

    /**
     * @return Collection|Surrogate[]
     */
    public function onlySurrogates()
    {
        return $this->all()
            ->getSurrogates();
    }

    /**
     * @param Block $block
     * @return Codepoint\Range\Collection
     * @throws Repository\BlockNotFoundException
     */
    public function getCodepointsByBlock(Block $block)
    {
        return $this->sourceRepository
            ->getCodepointsByBlock($block);
    }

    /**
     * @param Block $block
     * @return Collection|CodepointAssigned[]
     */
    public function getByBlock(Block $block)
    {
        return $this->getByCodepointRanges(
            $this->getCodepointsByBlock($block)
        );
    }

    /**
     * @param GeneralCategory $category
     * @return Codepoint\Range\Collection
     * @throws Repository\BlockNotFoundException
     */
    public function getCodepointsByCategory(GeneralCategory $category)
    {
        return $this->sourceRepository
            ->getCodepointsByCategory($category);
    }

    /**
     * @param GeneralCategory $category
     * @return Collection|CodepointAssigned[]
     */
    public function getByCategory(GeneralCategory $category)
    {
        return $this->getByCodepointRanges(
            $this->getCodepointsByCategory($category)
        );
    }

    /**
     * @param Script $script
     * @return Codepoint\Range\Collection
     * @throws Repository\BlockNotFoundException
     */
    public function getCodepointsByScript(Script $script)
    {
        return $this->sourceRepository
            ->getCodepointsByScript($script);
    }

    /**
     * @param Script $script
     * @return Collection|CodepointAssigned[]
     */
    public function getByScript(Script $script)
    {
        return $this->getByCodepointRanges(
            $this->getCodepointsByScript($script)
        );
    }

    /**
     * @param Codepoint\Range\Collection $ranges
     * @return CodepointAssigned[]
     */
    private function getByCodepointRanges(Codepoint\Range\Collection $ranges)
    {
        return $this->getByCodepoints(
            $ranges->expand()
        );
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
        $dbPathInfo = new \SplFileInfo(sprintf('%s/../../resources/generated/ucd', __DIR__));
        $charactersDirectory = PHPRangeFileDirectory::fromPath($dbPathInfo);
        $propsPathInfo = new \SplFileInfo(sprintf('%s/../../resources/generated/props', __DIR__));
        $propertiesDirectory = FileRepository\PropertyFile\PHPPropertyFileDirectory::fromPath($propsPathInfo);
        $aggregators = new FileRepository\PropertyAggregators();
        $serializer = new PHPSerializer();
        $sliceSize = FileRepository::DEFAULT_SLICE_SIZE;
        $fileCache = new FileRepository\RangeFileCache();

        return new FileRepository(
            $charactersDirectory,
            $propertiesDirectory,
            $aggregators,
            $serializer,
            $sliceSize,
            $fileCache
        );
    }
}