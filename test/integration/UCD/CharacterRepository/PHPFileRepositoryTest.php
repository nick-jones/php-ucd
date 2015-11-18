<?php

namespace integration\UCD\CharacterRepository;

use UCD\Console\Application\Container\ConfigurationProvider;
use UCD\Unicode\Codepoint;

class PHPFileRepositoryTest extends TestCase
{
    protected function setUp()
    {
        $directoryPath = $this->fs->path('/db');
        mkdir($directoryPath);

        $path = $this->fs->path('/db/00000000-00000000!0001.php');
        $character = $this->buildCharacterWithCodepoint(Codepoint::fromInt(0));
        $content = sprintf("<?php\nreturn %s;", var_export([0 => serialize($character)], true));
        file_put_contents($path, $content);

        $this->registerContainerProviders();
        $this->container[ConfigurationProvider::CONFIG_KEY_DB_PATH] = $directoryPath;
        $this->repository = $this->container['repository.php'];
    }
}