<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Character;
use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Character\WritableRepository;

use UCD\Infrastructure\Repository\CharacterRepository\FileRepository;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\CharacterSlicer;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPSerializer;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFile;

class PHPFileRepository implements WritableRepository
{
    const DEFAULT_DB_DIR = '../../../../../resources/generated/db';
    const DEFAULT_SLICE_SIZE = 1000;

    /**
     * @var PHPFileDirectory|PHPRangeFile[]
     */
    private $directory;

    /**
     * @var PHPSerializer
     */
    private $serializer;

    /**
     * @param PHPFileDirectory $directory
     * @param \UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPSerializer $serializer
     */
    public function __construct(PHPFileDirectory $directory = null, PHPSerializer $serializer = null)
    {
        $this->directory = $directory ?: new PHPFileDirectory($this->defaultDbDirectory());
        $this->serializer = $serializer ?: new PHPSerializer();
    }

    /**
     * @return \SplFileInfo
     */
    private function defaultDbDirectory()
    {
        $path = sprintf('%s/%s', __DIR__, self::DEFAULT_DB_DIR);

        return new \SplFileInfo($path);
    }

    /**
     * @param Codepoint $codepoint
     * @return Character
     * @throws CharacterNotFoundException
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
     * @param Character[] $characters
     */
    public function addMany($characters)
    {
        $slices = CharacterSlicer::slice($characters, self::DEFAULT_SLICE_SIZE);

        foreach ($slices as $range => $chunk) {
            /** @var Range $range */
            $this->createFileWithCharacters($range, $chunk);
        }
    }

    /**
     * @param Range $range
     * @param Character[] $characters
     * @return Character[]
     */
    private function createFileWithCharacters(Range $range, array $characters)
    {
        $characters = $this->flattenCharacters($characters);

        $rangeFile = $this->directory->createFileFromDetails($range, count($characters));
        $rangeFile->write($characters);
    }

    /**
     * @param Character[] $characters
     * @return string[]
     */
    private function flattenCharacters(array $characters)
    {
        $flattened = [];

        foreach ($characters as $character) {
            $key = $character->getCodepointValue();
            $flattened[$key] = $this->serializer->serialize($character);;
        }

        return $flattened;
    }

    /**
     * @return Character[]
     */
    public function getAll()
    {
        foreach ($this->directory as $file) {
            $characters = $file->read();

            foreach ($characters as $i => $character) {
                yield $i => $this->serializer->unserialize($character);
            }
        }
    }

    /**
     * @return int
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