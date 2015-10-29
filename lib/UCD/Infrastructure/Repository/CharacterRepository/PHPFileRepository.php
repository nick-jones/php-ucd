<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Character\Collection;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Character\WritableRepository;
use UCD\Entity\CodepointAssigned;
use UCD\Entity\Character\Repository;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\CharacterSlicer;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Serializer;

class PHPFileRepository implements WritableRepository
{
    use Repository\Capability\Notify;

    const DEFAULT_SLICE_SIZE = 1000;

    /**
     * @var PHPFileDirectory|PHPRangeFile[]
     */
    private $directory;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var int
     */
    private $sliceSize;

    /**
     * @param PHPFileDirectory $directory
     * @param Serializer $serializer
     * @param int $sliceSize
     */
    public function __construct(
        PHPFileDirectory $directory,
        Serializer $serializer,
        $sliceSize = self::DEFAULT_SLICE_SIZE
    ) {
        $this->directory = $directory;
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
     * @return PHPRangeFile
     * @throws CharacterNotFoundException
     */
    private function getFileByCodepoint(Codepoint $codepoint)
    {
        $file = $this->directory->getFileFromValue($codepoint->getValue());

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

        $rangeFile = $this->directory->createFileFromDetails($range, count($characters));
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
        foreach ($this->directory as $file) {
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
        $tally = 0;

        foreach ($this->directory as $file) {
            $tally += $file->getTotal();
        }

        return $tally;
    }
}