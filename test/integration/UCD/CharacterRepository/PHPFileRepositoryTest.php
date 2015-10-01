<?php

namespace integration\UCD\CharacterRepository;

use UCD\Entity\Codepoint;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPSerializer;
use UCD\Infrastructure\Repository\CharacterRepository\PHPFileRepository;
use VirtualFileSystem\FileSystem;

class PHPFileRepositoryTest extends TestCase
{
    /**
     * @var FileSystem
     */
    protected $fs;

    protected function setUp()
    {
        $this->fs = new FileSystem();

        $directoryPath = $this->fs->path('/db');
        mkdir($directoryPath);

        $path = $this->fs->path('/db/00000000-00000000!0001.php');
        $character = $this->buildCharacterWithCodepoint(Codepoint::fromInt(0));
        $content = sprintf("<?php\nreturn %s;", var_export([0 => serialize($character)], true));
        file_put_contents($path, $content);

        $directory = new PHPFileDirectory(new \SplFileInfo($directoryPath));
        $this->repository = new PHPFileRepository($directory, new PHPSerializer());
    }
}