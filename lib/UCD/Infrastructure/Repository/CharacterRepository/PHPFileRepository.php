<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use UCD\Entity\Character;
use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\Repository\CharacterNotFoundException;

use UCD\Entity\Character\WritableRepository;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\CharacterSlicer;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPRangeFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPRangeFiles;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPSerializer;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Range;

class PHPFileRepository implements WritableRepository
{
    const DEFAULT_DB_DIR = __DIR__ . '/../../../../../resources/generated/db';
    const DEFAULT_SLICE_SIZE = 1000;

    /**
     * @var string
     */
    private $dbPath;

    /**
     * @var PHPRangeFiles|PHPRangeFile[]
     */
    private $files;

    /**
     * @var PHPSerializer
     */
    private $serializer;

    /**
     * @param string $dbPath
     * @param PHPRangeFiles $files
     * @param PHPSerializer $serializer
     */
    public function __construct(
        $dbPath = self::DEFAULT_DB_DIR,
        PHPRangeFiles $files = null,
        PHPSerializer $serializer = null
    ) {
        $this->dbPath = $dbPath;
        $this->files = $files ?: PHPRangeFiles::fromDirectory($dbPath);
        $this->serializer = $serializer ?: new PHPSerializer();
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
        $file = $this->files->getForValue($codepoint->getValue());

        if ($file === null) {
            throw CharacterNotFoundException::withCodepoint($codepoint);
        }

        return $file;
    }

    /**
     * @param Character[]|\Traversable $characters
     */
    public function addMany($characters)
    {
        $slices = CharacterSlicer::slice($characters, self::DEFAULT_SLICE_SIZE);

        foreach ($slices as $range => $chunk) {
            /** @var Range $range */
            $this->addChunk($range, $chunk);
        }
    }

    /**
     * @param Range $range
     * @param Character[] $characters
     * @return Character[]
     */
    private function addChunk(Range $range, array $characters)
    {
        $characters = $this->flattenCharacters($characters);

        $rangeFile = $this->files->addFromDetails($this->dbPath, $range, count($characters));
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
     * @return Character[]|\Traversable
     */
    public function getAll()
    {
        foreach ($this->files as $file) {
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

        foreach ($this->files as $file) {
            $tally += $file->getTotal();
        }

        return $tally;
    }
}