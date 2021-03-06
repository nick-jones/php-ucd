<?php

namespace integration\UCD\Command;

use integration\UCD\TestCase as BaseTestCase;

use Hamcrest\MatcherAssert as ha;
use Hamcrest\Matchers as hm;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

use UCD\Console\Application\Command\RepositoryTransferCommand;
use UCD\Unicode\Character\Collection;
use UCD\Unicode\Codepoint;
use UCD\Infrastructure\Repository\CharacterRepository\InMemoryRepository;

class RepositoryTransferCommandTest extends BaseTestCase
{
    /**
     * @var CommandTester
     */
    protected $commandTester;

    protected function setUp()
    {
        $application = new Application();
        $application->add(new RepositoryTransferCommand($this->container));
        $command = $application->get(RepositoryTransferCommand::COMMAND_NAME);
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @test
     */
    public function it_transfers_characters_from_one_repository_to_another()
    {
        $codepoint = Codepoint::fromInt(1);
        $character = $this->buildCharacterWithCodepoint($codepoint);
        $characters = Collection::fromArray([$character]);
        $source = new InMemoryRepository();
        $source->addMany($characters);
        $destination = new InMemoryRepository();

        $this->container['repository.test-source'] = $source;
        $this->container['repository.test-destination'] = $destination;

        ha::assertThat(count($source), hm::is(hm::identicalTo(1)));
        ha::assertThat(count($destination), hm::is(hm::identicalTo(0)));

        $this->commandTester->execute([
            'command' => RepositoryTransferCommand::COMMAND_NAME,
            'from' => 'test-source',
            'to' => 'test-destination'
        ]);

        ha::assertThat(count($source), hm::is(hm::identicalTo(1)));
        ha::assertThat(count($destination), hm::is(hm::identicalTo(1)));

        $output = $this->commandTester->getDisplay();
        $statusCode = $this->commandTester->getStatusCode();

        ha::assertThat('output', $output, hm::containsString('Database Generated'));
        ha::assertThat('status code', $statusCode, hm::is(hm::identicalTo(0)));
    }
}