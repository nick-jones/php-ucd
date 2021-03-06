<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Exception;
use UCD\Unicode\Character;
use UCD\Unicode\Character\Collection;
use UCD\Unicode\Character\Properties;
use UCD\Unicode\Character\Repository;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Character\WritableRepository;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Codepoint\AggregatorRelay;
use UCD\Unicode\CodepointAssigned;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\CharacterSlicer;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Property;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyAggregators;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFileCache;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Serializer;

class FileRepository implements WritableRepository
{
    use Repository\Capability\Notify;

    const DEFAULT_SLICE_SIZE = 1000;

    /**
     * @var RangeFileDirectory
     */
    private $charactersDirectory;

    /**
     * @var PropertyFileDirectory
     */
    private $propertiesDirectory;

    /**
     * @var PropertyAggregators|AggregatorRelay[]
     */
    private $aggregators;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var int
     */
    private $sliceSize;

    /**
     * @var RangeFileCache
     */
    private $fileCache;

    /**
     * @param RangeFileDirectory $charactersDirectory
     * @param PropertyFileDirectory $propertiesDirectory
     * @param PropertyAggregators $aggregators
     * @param Serializer $serializer
     * @param int $sliceSize
     * @param RangeFileCache|null $fileCache
     */
    public function __construct(
        RangeFileDirectory $charactersDirectory,
        PropertyFileDirectory $propertiesDirectory,
        PropertyAggregators $aggregators,
        Serializer $serializer,
        $sliceSize = self::DEFAULT_SLICE_SIZE,
        RangeFileCache $fileCache = null
    ) {
        $this->charactersDirectory = $charactersDirectory;
        $this->serializer = $serializer;
        $this->sliceSize = $sliceSize;
        $this->propertiesDirectory = $propertiesDirectory;
        $this->aggregators = $aggregators;
        $this->fileCache = $fileCache;
    }

    /**
     * {@inheritDoc}
     */
    public function getByCodepoint(Codepoint $codepoint)
    {
        $value = $codepoint->getValue();
        $file = $this->getFileByCodepoint($codepoint);
        $characters = $this->fileCache ? $this->fileCache->read($file) : $file->read();

        if (!array_key_exists($value, $characters)) {
            throw CharacterNotFoundException::withCodepoint($codepoint);
        }

        return $this->serializer->unserialize($characters[$value]);
    }

    /**
     * {@inheritDoc}
     */
    public function getByCodepoints(Codepoint\Collection $codepoints)
    {
        return new Character\Collection(
            $this->yieldByCodepoints($codepoints)
        );
    }

    /**
     * @param Codepoint\Collection $codepoints
     * @return \Generator
     */
    private function yieldByCodepoints(Codepoint\Collection $codepoints)
    {
        foreach ($codepoints as $codepoint) {
            try {
                yield $this->getByCodepoint($codepoint);
            } catch (CharacterNotFoundException $e) {
                // Missing characters are skipped
            }
        }
    }

    /**
     * @param Codepoint $codepoint
     * @return RangeFile
     * @throws CharacterNotFoundException
     */
    private function getFileByCodepoint(Codepoint $codepoint)
    {
        $file = $this->charactersDirectory->getFileFromValue($codepoint->getValue());

        if ($file === null) {
            throw CharacterNotFoundException::withCodepoint($codepoint);
        }

        return $file;
    }

    /**
     * {@inheritDoc}
     */
    public function addMany(Collection $characters)
    {
        $slices = CharacterSlicer::slice($characters, $this->sliceSize);

        foreach ($slices as $range => $chunk) {
            /** @var Range $range */
            $this->createFileWithCharacters($range, $chunk);
            $this->addCharactersToAggregators($chunk);
            $this->notify();
        }

        $this->writeAggregations();
    }

    /**
     * @param Range $range
     * @param CodepointAssigned[] $characters
     * @return CodepointAssigned[]
     */
    private function createFileWithCharacters(Range $range, array $characters)
    {
        $this->charactersDirectory->writeRange(
            $range,
            $this->flattenCharacters($characters)
        );
    }

    /**
     * @param CodepointAssigned[] $characters
     * @return string[]
     */
    private function flattenCharacters(array $characters)
    {
        $flattened = [];

        foreach ($characters as $character) {
            $codepoint = $character->getCodepoint();
            $key = $codepoint->getValue();
            $flattened[$key] = $this->serializer->serialize($character);;
        }

        return $flattened;
    }

    /**
     * @param Codepoint\Range\Collection[] $ranges
     * @return string[]
     */
    public function flattenRanges(array $ranges)
    {
        $flattened = [];

        foreach ($ranges as $key => $range) {
            $range = $range->toArray();
            $flattened[$key] = $this->serializer->serialize($range);
        }

        return $flattened;
    }

    /**
     * @param CodepointAssigned[] $characters
     */
    private function addCharactersToAggregators(array $characters)
    {
        $this->aggregators->addCharacters($characters);
    }

    /**
     * @return bool
     */
    private function writeAggregations()
    {
        foreach ($this->aggregators as $property => $aggregator) {
            /** @var Property $property */
            $ranges = $this->flattenRanges($aggregator->getAllRanges());
            $this->propertiesDirectory->writeProperty($property, $ranges);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        return new Collection(
            $this->readAll()
        );
    }

    /**
     * @return \Generator
     */
    private function readAll()
    {
        $files = $this->charactersDirectory->getFiles();

        foreach ($files as $file) {
            $characters = $this->fileCache ? $this->fileCache->read($file) : $file->read();

            foreach ($characters as $i => $character) {
                yield $i => $this->serializer->unserialize($character);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getCodepointsByBlock(Properties\General\Block $block)
    {
        return $this->resolveCodepointsByProperty(
            Property::ofType(Property::BLOCK),
            (string)$block,
            Repository\BlockNotFoundException::withBlock($block)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getCodepointsByCategory(Properties\General\GeneralCategory $category)
    {
        return $this->resolveCodepointsByProperty(
            Property::ofType(Property::GENERAL_CATEGORY),
            (string)$category,
            Repository\GeneralCategoryNotFoundException::withCategory($category)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getCodepointsByScript(Properties\General\Script $script)
    {
        return $this->resolveCodepointsByProperty(
            Property::ofType(Property::SCRIPT),
            (string)$script,
            Repository\ScriptNotFoundException::withScript($script)
        );
    }

    /**
     * @param Property $property
     * @param string $key
     * @param Exception $notFoundException
     * @return Codepoint\Range[]
     * @throws Exception
     */
    private function resolveCodepointsByProperty(Property $property, $key, Exception $notFoundException)
    {
        $file = $this->propertiesDirectory->getFileForProperty($property);
        $map = $file->read();

        $codepoints = array_key_exists($key, $map)
            ? $this->serializer->unserialize($map[$key])
            : [];

        if (count($codepoints) === 0) {
            throw $notFoundException;
        }

        return Codepoint\Range\Collection::fromArray($codepoints);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        $files = $this->charactersDirectory->getFiles();
        $tally = 0;

        foreach ($files as $file) {
            $tally += $file->getTotal();
        }

        return $tally;
    }
}