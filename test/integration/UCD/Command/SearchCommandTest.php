<?php

namespace integration\UCD\Command;

use integration\UCD\TestCase as BaseTestCase;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

use UCD\Console\Command\SearchCommand;
use UCD\Entity\Character\Codepoint;

use VirtualFileSystem\FileSystem;

use Hamcrest\MatcherAssert as ha;
use Hamcrest\Matchers as hm;

class SearchCommandTest extends BaseTestCase
{
    /**
     * @var CommandTester
     */
    protected $commandTester;

    /**
     * @var FileSystem
     */
    protected $fs;

    /**
     * @var string
     */
    protected $dbPath;

    protected function setUp()
    {
        $application = new Application();
        $application->add(new SearchCommand());
        $command = $application->get('search');
        $this->commandTester = new CommandTester($command);

        $this->fs = new FileSystem();
        $this->dbPath = $this->fs->path('/');

        $character = $this->buildCharacterWithCodepoint(Codepoint::fromInt(163));
        $content = sprintf("<?php\nreturn %s;", var_export([163 => serialize($character)], true));
        file_put_contents($this->fs->path('/00000000-01114111!0001.php'), $content);
    }

    /**
     * @test
     */
    public function it_displays_details_for_resolved_characters()
    {
        $this->commandTester->execute([
            'command' => SearchCommand::COMMAND_NAME,
            '--db-location' => $this->dbPath,
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
            '--db-location' => $this->dbPath,
            'codepoint' => '1'
        ]);

        $output = $this->commandTester->getDisplay();
        $statusCode = $this->commandTester->getStatusCode();

        ha::assertThat('output', $output, hm::containsString('Character Not Found'));
        ha::assertThat('status code', $statusCode, hm::is(hm::identicalTo(1)));
    }
}