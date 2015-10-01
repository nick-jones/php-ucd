<?php

namespace UCD\Application\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;

class RepositoryTransferCommand extends RepositoryUtilisingCommand
{
    const COMMAND_NAME = 'repository-transfer';
    const ARGUMENT_FROM = 'from';
    const ARGUMENT_TO = 'to';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Reads characters from one repository and adds them to another');
        $this->setDefinition($this->createInputDefinition());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);
        $from = $input->getArgument(self::ARGUMENT_FROM);
        $to = $input->getArgument(self::ARGUMENT_TO);

        $source = $this->getRepositoryByName($from);
        $destination = $this->getRepositoryByName($to);

        $destination->addMany(
            $source->getAll()
        );

        $output->writeln('<info>Database Generated</info>');
        $output->writeln(sprintf('Memory peak: %.5f MB', memory_get_peak_usage() / 1048576));
        $output->writeln(sprintf('Took: %.5f seconds', microtime(true) - $start));

        return 0;
    }

    /**
     * @return InputDefinition
     */
    private function createInputDefinition()
    {
        $readNamesList = implode(', ', $this->getRepositoryNames());
        $writeNamesList = implode(', ', $this->getWritableRepositoryNames());

        $from = new InputArgument(
            self::ARGUMENT_FROM,
            InputArgument::REQUIRED,
            sprintf('Repository from which the characters should be retrieved. Choose from: %s', $readNamesList)
        );

        $to = new InputArgument(
            self::ARGUMENT_TO,
            InputArgument::REQUIRED,
            sprintf('Repository to which the characters should be added. Choose from: %s', $writeNamesList)
        );

        return new InputDefinition([$from, $to]);
    }
}