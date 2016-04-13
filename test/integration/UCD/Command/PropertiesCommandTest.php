<?php

namespace integration\UCD\Command;

use integration\UCD\TestCase as BaseTestCase;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

use Hamcrest\MatcherAssert as ha;
use Hamcrest\Matchers as hm;

use UCD\Console\Application\Command\PropertiesCommand;
use UCD\Infrastructure\Repository\CharacterRepository\InMemoryRepository;
use UCD\Unicode\Character\Collection;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Character\Properties\General\Script;
use UCD\Unicode\Codepoint;

class PropertiesCommandTest extends BaseTestCase
{
    /**
     * @var CommandTester
     */
    protected $commandTester;

    protected function setUp()
    {
        $application = new Application();
        $application->add(new PropertiesCommand($this->container));
        $command = $application->get(PropertiesCommand::COMMAND_NAME);
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @test
     */
    public function it_displays_characters_residing_in_a_supplied_block()
    {
        $repository = new InMemoryRepository();
        $codepoint = Codepoint::fromInt(97);
        $block = Block::fromValue(Block::BASIC_LATIN);
        $character = $this->buildCharacterWithCodepoint($codepoint, $block);
        $characters = Collection::fromArray([$character]);
        $repository->addMany($characters);

        $this->container['repository.test'] = $repository;

        $this->commandTester->execute([
            'command' => PropertiesCommand::COMMAND_NAME,
            '--from' => 'test',
            'property-type' => PropertiesCommand::PROPERTY_BLOCK,
            'value' => $block->getValue()
        ]);

        $output = $this->commandTester->getDisplay();
        $statusCode = $this->commandTester->getStatusCode();

        ha::assertThat('output', $output, hm::containsString('U+61:'));
        ha::assertThat('status code', $statusCode, hm::is(hm::identicalTo(0)));
    }

    /**
     * @test
     */
    public function it_displays_characters_residing_in_a_supplied_category()
    {
        $repository = new InMemoryRepository();
        $codepoint = Codepoint::fromInt(97);
        $category = GeneralCategory::fromValue(GeneralCategory::LETTER_LOWERCASE);
        $character = $this->buildCharacterWithCodepoint($codepoint, null, $category);
        $characters = Collection::fromArray([$character]);
        $repository->addMany($characters);

        $this->container['repository.test'] = $repository;

        $this->commandTester->execute([
            'command' => PropertiesCommand::COMMAND_NAME,
            '--from' => 'test',
            'property-type' => PropertiesCommand::PROPERTY_CATEGORY,
            'value' => $category->getValue()
        ]);

        $output = $this->commandTester->getDisplay();
        $statusCode = $this->commandTester->getStatusCode();

        ha::assertThat('output', $output, hm::containsString('U+61:'));
        ha::assertThat('status code', $statusCode, hm::is(hm::identicalTo(0)));
    }

    /**
     * @test
     */
    public function it_displays_characters_residing_in_a_supplied_script()
    {
        $repository = new InMemoryRepository();
        $codepoint = Codepoint::fromInt(97);
        $script = Script::fromValue(Script::LATIN);
        $character = $this->buildCharacterWithCodepoint($codepoint, null, null, $script);
        $characters = Collection::fromArray([$character]);
        $repository->addMany($characters);

        $this->container['repository.test'] = $repository;

        $this->commandTester->execute([
            'command' => PropertiesCommand::COMMAND_NAME,
            '--from' => 'test',
            'property-type' => PropertiesCommand::PROPERTY_SCRIPT,
            'value' => $script->getValue()
        ]);

        $output = $this->commandTester->getDisplay();
        $statusCode = $this->commandTester->getStatusCode();

        ha::assertThat('output', $output, hm::containsString('U+61:'));
        ha::assertThat('status code', $statusCode, hm::is(hm::identicalTo(0)));
    }
}