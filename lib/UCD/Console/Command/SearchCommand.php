<?php

namespace UCD\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\Repository\CharacterNotFoundException;

use UCD\Infrastructure\Repository\CharacterRepository\PHPFileRepository;
use UCD\View\CharacterView;

class SearchCommand extends Command
{
    const COMMAND_NAME = 'search';
    const ARGUMENT_CODEPOINT = 'codepoint';
    const OPTION_DB_LOCATION = 'db-location';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Search the character database by codepoint');
        $this->setDefinition($this->createInputDefinition());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $codepoint = Codepoint::fromInt((int)$input->getArgument(self::ARGUMENT_CODEPOINT));
        $databaseLocation = $input->getOption(self::OPTION_DB_LOCATION);
        $repository = new PHPFileRepository($databaseLocation);

        try {
            $character = $repository->getByCodepoint($codepoint);
        } catch (CharacterNotFoundException $e) {
            $output->writeln('<error>Character Not Found</error>');
            return 1;
        }

        $view = new CharacterView($character);

        $output->writeln('<info>Character Found</info>');
        $output->writeln(sprintf('Export: %s', $view->asExport()));
        $output->writeln(sprintf('UTF-8: %s', $view->asUTF8()));

        return 0;
    }

    /**
     * @return InputDefinition
     */
    private function createInputDefinition()
    {
        $codepoint = new InputArgument(
            self::ARGUMENT_CODEPOINT,
            InputArgument::REQUIRED,
            ''
        );

        $databaseLocation = new InputOption(
            self::OPTION_DB_LOCATION,
            null,
            InputOption::VALUE_OPTIONAL,
            'Location of the file database',
            __DIR__ . '/../../../../resources/generated/db/'
        );

        return new InputDefinition([
            $codepoint,
            $databaseLocation,
        ]);
    }
}