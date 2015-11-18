<?php

namespace integration\UCD\Command;

use Hamcrest\MatcherAssert as ha;
use Hamcrest\Matchers as hm;
use integration\UCD\TestCase as BaseTestCase;
use Pimple\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use UCD\Console\Application\Command\SearchCommand;
use UCD\Unicode\Character\Collection;
use UCD\Unicode\Codepoint;
use UCD\Infrastructure\Repository\CharacterRepository\InMemoryRepository;

class SearchCommandTest extends BaseTestCase
{
    /**
     * @var CommandTester
     */
    protected $commandTester;

    protected function setUp()
    {
        $application = new Application();
        $application->add(new SearchCommand($this->container));
        $command = $application->get(SearchCommand::COMMAND_NAME);
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @test
     */
    public function it_displays_details_for_resolved_characters()
    {
        $repository = new InMemoryRepository();
        $codepoint = Codepoint::fromInt(163);
        $character = $this->buildCharacterWithCodepoint($codepoint);
        $characters = Collection::fromArray([$character]);
        $repository->addMany($characters);

        $this->container['repository.test'] = $repository;

        $this->commandTester->execute([
            'command' => SearchCommand::COMMAND_NAME,
            '--from' => 'test',
            '--enc' => SearchCommand::ENCODING_DECIMAL,
            'codepoint' => $codepoint->getValue()
        ]);

        $output = $this->commandTester->getDisplay();
        $statusCode = $this->commandTester->getStatusCode();

        ha::assertThat('output', $output, hm::containsString('Character Found'));
        ha::assertThat('output', $output, hm::containsString('Export: UCD\Unicode\Character'));
        ha::assertThat('output', $output, hm::containsString('UTF-8: £'));
        ha::assertThat('status code', $statusCode, hm::is(hm::identicalTo(0)));
    }

    /**
     * @test
     */
    public function it_displays_an_error_for_unresolved_characters()
    {
        $this->container['repository.test'] = new InMemoryRepository();

        $this->commandTester->execute([
            'command' => SearchCommand::COMMAND_NAME,
            '--from' => 'test',
            'codepoint' => '1'
        ]);

        $output = $this->commandTester->getDisplay();
        $statusCode = $this->commandTester->getStatusCode();

        ha::assertThat('output', $output, hm::containsString('Character Not Found'));
        ha::assertThat('status code', $statusCode, hm::is(hm::identicalTo(1)));
    }
}