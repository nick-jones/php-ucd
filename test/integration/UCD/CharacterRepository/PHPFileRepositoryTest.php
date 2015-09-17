<?php

namespace integration\UCD\CharacterRepository;

use UCD\Entity\Character\Codepoint;
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
        $path = $this->fs->path('/00000000-00000000!0001.php');
        $character = $this->buildCharacterWithCodepoint(Codepoint::fromInt(0));
        $content = sprintf("<?php\nreturn %s;", var_export([0 => serialize($character)], true));
        file_put_contents($path, $content);

        $this->repository = new PHPFileRepository($this->fs->path('/'));
    }
}