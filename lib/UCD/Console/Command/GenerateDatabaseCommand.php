<?php

namespace UCD\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use UCD\Entity\Character\ReadOnlyRepository;
use UCD\Entity\Character\WritableRepository;

use UCD\Infrastructure\Repository\CharacterRepository\DebugWritableRepository;
use UCD\Infrastructure\Repository\CharacterRepository\PHPFileRepository;
use UCD\Infrastructure\Repository\CharacterRepository\NULLRepository;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\CharacterElementParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\StreamingCharacterReader;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\XMLReader;

use fool\echolog\Echolog;

class GenerateDatabaseCommand extends Command
{
    const COMMAND_NAME = 'generate-database';
    const ARGUMENT_UCDXML_LOCATION = 'ucdxml-location';
    const OPTION_DB_LOCATION = 'db-location';
    const OPTION_DEBUG = 'debug';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Generates a cache of codepoint details from the UCD');
        $this->setDefinition($this->createInputDefinition());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ucdXmlLocation = $input->getArgument(self::ARGUMENT_UCDXML_LOCATION);
        $databaseLocation = $input->getOption(self::OPTION_DB_LOCATION);
        $debug = $input->getOption(self::OPTION_DEBUG);

        $this->prepareDestination($databaseLocation);
        $source = $this->getXMLRepository($ucdXmlLocation);
        $destination = $this->getDestinationRepository($databaseLocation, $debug);

        $destination->addMany(
            $source->getAll()
        );

        if ($debug === true) {
            $output->writeln('<info>Database Not Generated</info>');
        } else {
            $output->writeln('<info>Database Generated</info>');
        }

        return 0;
    }

    /**
     * @param string $databaseLocation
     */
    private function prepareDestination($databaseLocation)
    {
        if (!is_dir($databaseLocation)) {
            mkdir($databaseLocation, 0777, true);
        }
    }

    /**
     * @param string $ucdXmlLocation
     * @return ReadOnlyRepository
     */
    private function getXMLRepository($ucdXmlLocation)
    {
        $xmlReader = new XMLReader();
        $xmlReader->open($ucdXmlLocation);

        $elementReader = new StreamingCharacterReader($xmlReader);
        $elementParser = new CharacterElementParser();

        return new XMLRepository($elementReader, $elementParser);
    }

    /**
     * @param string $databaseLocation
     * @param bool $debug
     * @return WritableRepository
     */
    private function getDestinationRepository($databaseLocation, $debug = false)
    {
        return ($debug === true)
            ? new DebugWritableRepository(new NULLRepository(), new Echolog())
            : new PHPFileRepository($databaseLocation);
    }

    /**
     * @return InputDefinition
     */
    private function createInputDefinition()
    {
        $ucdXmlLocation = new InputArgument(
            self::ARGUMENT_UCDXML_LOCATION,
            InputArgument::REQUIRED,
            'Location of the UCDXML file from which the database will be generated'
        );

        $databaseLocation = new InputOption(
            self::OPTION_DB_LOCATION,
            null,
            InputOption::VALUE_OPTIONAL,
            'Location to dump the generated database',
            __DIR__ . '/../../../../resources/generated/db/'
        );

        $debug = new InputOption(
            self::OPTION_DEBUG,
            null,
            InputOption::VALUE_NONE,
            'Dump the characters to be written, instead of writing them'
        );

        return new InputDefinition([
            $ucdXmlLocation,
            $databaseLocation,
            $debug
        ]);
    }
}