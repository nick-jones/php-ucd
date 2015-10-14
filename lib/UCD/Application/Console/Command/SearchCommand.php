<?php

namespace UCD\Application\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use UCD\Collection;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\View\CharacterView;

class SearchCommand extends RepositoryUtilisingCommand
{
    const COMMAND_NAME = 'search';
    const ARGUMENT_CODEPOINT = 'codepoint';
    const OPTION_FROM = 'from';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Search a character repository by codepoint');
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
        $codepoint = (int)$input->getArgument(self::ARGUMENT_CODEPOINT);
        $from = $input->getOption(self::OPTION_FROM);
        $repository = $this->getRepositoryByName($from);
        $collection = new Collection($repository);

        try {
            $character = $collection->getCharacterByCodepoint($codepoint);
        } catch (CharacterNotFoundException $e) {
            $output->writeln('<error>Character Not Found</error>');
            return 1;
        }

        $view = new CharacterView($character);

        $output->writeln('<info>Character Found</info>');
        $output->writeln(sprintf('Export: %s', $view->asExport()));
        $output->writeln(sprintf('UTF-8: %s', $view->asUTF8()));
        $output->writeln(sprintf('Memory peak: %.5f MB', memory_get_peak_usage() / 1048576));
        $output->writeln(sprintf('Took: %.5f seconds', microtime(true) - $start));

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
            'Character codepoint to search for'
        );

        $repositoryNames = $this->getRepositoryNames();
        $namesList = implode(', ', $repositoryNames);

        $from = new InputOption(
            self::OPTION_FROM,
            null,
            InputOption::VALUE_OPTIONAL,
            sprintf('Repository from which the character should be resolved. Choose from: %s', $namesList),
            array_shift($repositoryNames)
        );

        return new InputDefinition([$codepoint, $from]);
    }

}