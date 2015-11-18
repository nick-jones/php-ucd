<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Unicode\Character\Collection;
use UCD\Unicode\Character\Repository;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Character\WritableRepository;
use UCD\Unicode\Codepoint;
use UCD\Unicode\CodepointAssigned;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\CharacterSlicer;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile;
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
     * @var Serializer
     */
    private $serializer;

    /**
     * @var int
     */
    private $sliceSize;

    /**
     * @param RangeFileDirectory $charactersDirectory
     * @param Serializer $serializer
     * @param int $sliceSize
     */
    public function __construct(
        RangeFileDirectory $charactersDirectory,
        Serializer $serializer,
        $sliceSize = self::DEFAULT_SLICE_SIZE
    ) {
        $this->charactersDirectory = $charactersDirectory;
        $this->serializer = $serializer;
        $this->sliceSize = $sliceSize;
    }

    /**
     * {@inheritDoc}
     */
    public function getByCodepoint(Codepoint $codepoint)
    {
        $value = $codepoint->getValue();
        $file = $this->getFileByCodepoint($codepoint);
        $characters = $file->read();

        if (!array_key_exists($value, $characters)) {
            throw CharacterNotFoundException::withCodepoint($codepoint);
        }

        return $this->serializer->unserialize($characters[$value]);
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
            $this->notify();
        }
    }

    /**
     * @param Range $range
     * @param CodepointAssigned[] $characters
     * @return CodepointAssigned[]
     */
    private function createFileWithCharacters(Range $range, array $characters)
    {
        $characters = $this->flattenCharacters($characters);

        $rangeFile = $this->charactersDirectory
            ->addFileFromRangeAndTotal($range, count($characters));

        $rangeFile->write($characters);
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
            $characters = $file->read();

            foreach ($characters as $i => $character) {
                yield $i => $this->serializer->unserialize($character);
            }
        }
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