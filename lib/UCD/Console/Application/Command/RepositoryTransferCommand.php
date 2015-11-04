<?php

namespace UCD\Console\Application\Command;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use UCD\Entity\Character\Repository;
use UCD\Entity\Character\WritableRepository;
use UCD\Exception\InvalidArgumentException;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;

class RepositoryTransferCommand extends RepositoryUtilisingCommand implements \SplObserver
{
    const COMMAND_NAME = 'repository-transfer';
    const ARGUMENT_FROM = 'from';
    const ARGUMENT_TO = 'to';

    /**
     * @var ProgressBar
     */
    protected $progress;

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
     * @throws InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);
        $from = $input->getArgument(self::ARGUMENT_FROM);
        $to = $input->getArgument(self::ARGUMENT_TO);

        if ($from === $to) {
            throw new InvalidArgumentException('Repositories must differ');
        }

        $source = $this->getRepositoryByName($from);
        $destination = $this->getWritableRepositoryByName($to);
        $this->setupProgressBar($output, $source, $destination);

        $characters = $source->getAll();
        $destination->addMany($characters);

        $this->tearDownProgressBar();

        $output->writeln('');
        $output->writeln('<info>Database Generated</info>');
        $output->writeln(sprintf('Memory peak: %.5f MB', memory_get_peak_usage() / 1048576));
        $output->writeln(sprintf('Took: %.5f seconds', microtime(true) - $start));

        return 0;
    }

    /**
     * @param OutputInterface $output
     * @param Repository $source
     * @param WritableRepository $destination
     */
    private function setupProgressBar(OutputInterface $output, Repository $source, WritableRepository $destination)
    {
        $this->progress = new ProgressBar($output, count($source));
        $this->progress->setMessage('Generating database...');
        $this->progress->start();

        $destination->attach($this);
    }

    private function tearDownProgressBar()
    {
        $this->progress->finish();
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

    /**
     * @param \SplSubject $subject
     */
    public function update(\SplSubject $subject)
    {
        if ($subject instanceof WritableRepository) {
            $this->progress->setProgress(count($subject));
        }
    }
}