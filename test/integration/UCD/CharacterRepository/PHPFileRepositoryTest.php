<?php

namespace integration\UCD\CharacterRepository;

use UCD\Console\Application\Container\ConfigurationProvider;
use UCD\Unicode\Codepoint;

class PHPFileRepositoryTest extends TestCase
{
    protected function setUp()
    {
        $dbPath = $this->fs->path('/db');
        $propsPath = $this->fs->path('/props');
        mkdir($dbPath);
        mkdir($propsPath);

        $codepoint = Codepoint::fromInt(0);
        $path = $this->fs->path('/db/00000000-00000000!0001.php');
        $character = $this->buildCharacterWithCodepoint($codepoint);
        $content = sprintf("<?php\nreturn %s;", var_export([0 => serialize($character)], true));
        file_put_contents($path, $content);

        $path = $this->fs->path('/props/block.php');
        $collection = Codepoint\Range\Collection::fromArray([Codepoint\Range::between($codepoint, $codepoint)]);
        $content = sprintf("<?php\nreturn %s;", var_export(['ASCII' => serialize($collection->toArray())], true));
        file_put_contents($path, $content);

        $path = $this->fs->path('/props/gc.php');
        $collection = Codepoint\Range\Collection::fromArray([Codepoint\Range::between($codepoint, $codepoint)]);
        $content = sprintf("<?php\nreturn %s;", var_export(['Cc' => serialize($collection->toArray())], true));
        file_put_contents($path, $content);

        $this->registerContainerProviders();
        $this->container[ConfigurationProvider::CONFIG_KEY_DB_PATH] = $dbPath;
        $this->container[ConfigurationProvider::CONFIG_KEY_PROPS_PATH] = $propsPath;
        $this->repository = $this->container['repository.php'];
    }
}