<?php

namespace integration\UCD\Command;

use integration\UCD\TestCase as BaseTestCase;

use Hamcrest\MatcherAssert as ha;
use Hamcrest\Matchers as hm;

use Pimple\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

use UCD\Application\Console\Command\SearchCommand;
use UCD\Application\Container\ConfigurationProvider;
use UCD\Application\Container\ServiceProvider;
use UCD\Entity\Character\Codepoint;

use VirtualFileSystem\FileSystem;

class SearchCommandTest extends BaseTestCase
{
    const CONFIG_KEY_REPO_PATH = 'config.repository.php.database_path';

    /**
     * @var CommandTester
     */
    protected $commandTester;

    /**
     * @var FileSystem
     */
    protected $fs;

    protected function setUp()
    {
        $this->fs = new FileSystem();

        $dbPath = $this->fs->path('/db');
        mkdir($dbPath);
        $character = $this->buildCharacterWithCodepoint(Codepoint::fromInt(163));
        $content = sprintf("<?php\nreturn %s;", var_export([163 => serialize($character)], true));
        file_put_contents($this->fs->path('/db/00000000-01114111!0001.php'), $content);

        $application = new Application();
        $container = new Container();
        $container->register(new ServiceProvider());
        $container->register(new ConfigurationProvider());
        $container[self::CONFIG_KEY_REPO_PATH] = $dbPath;
        $application->add(new SearchCommand($container));
        $command = $application->get(SearchCommand::COMMAND_NAME);
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @test
     */
    public function it_displays_details_for_resolved_characters()
    {
        $this->commandTester->execute([
            'command' => SearchCommand::COMMAND_NAME,
            '--from' => 'php',
            'codepoint' => '163'
        ]);

        $output = $this->commandTester->getDisplay();
        $statusCode = $this->commandTester->getStatusCode();

        ha::assertThat('output', $output, hm::containsString('Character Found'));
        ha::assertThat('output', $output, hm::containsString('Export: UCD\Entity\Character'));
        ha::assertThat('output', $output, hm::containsString('UTF-8: £'));
        ha::assertThat('status code', $statusCode, hm::is(hm::identicalTo(0)));
    }

    /**
     * @test
     */
    public function it_displays_an_error_for_unresolved_characters()
    {
        $this->commandTester->execute([
            'command' => SearchCommand::COMMAND_NAME,
            '--from' => 'php',
            'codepoint' => '1'
        ]);

        $output = $this->commandTester->getDisplay();
        $statusCode = $this->commandTester->getStatusCode();

        ha::assertThat('output', $output, hm::containsString('Character Not Found'));
        ha::assertThat('status code', $statusCode, hm::is(hm::identicalTo(1)));
    }
}