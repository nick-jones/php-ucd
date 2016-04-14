<?php

namespace UCD\Console\Application\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use UCD\Database;
use UCD\Exception\UnexpectedValueException;
use UCD\Unicode\Character\Collection;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Character\Properties\General\Script;
use UCD\Unicode\CodepointAssigned;

class PropertiesCommand extends RepositoryUtilisingCommand
{
    const OPTION_FROM = 'from';
    const ARGUMENT_PROPERTY_TYPE = 'property-type';
    const ARGUMENT_SEARCH_BY = 'value';
    const COMMAND_NAME = 'properties';
    const PROPERTY_BLOCK = 'block';
    const PROPERTY_CATEGORY = 'category';
    const PROPERTY_SCRIPT = 'script';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('List codepoints by property');
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
        $propertyType = $input->getArgument(self::ARGUMENT_PROPERTY_TYPE);
        $searchBy = $input->getArgument(self::ARGUMENT_SEARCH_BY);
        $from = $input->getOption(self::OPTION_FROM);
        $repository = $this->getRepositoryByName($from);
        $db = new Database($repository);
        $characters = $this->resolveCodepoints($db, $propertyType, $searchBy);

        $output->writeln(sprintf('<info>%s "%s"</info>', ucfirst($propertyType), $searchBy));

        foreach ($characters as $character) {
            $codepoint = $character->getCodepoint();
            $properties = $character->getGeneralProperties();
            $names = $properties->getNames();
            $primary = $names->getPrimary();
            $message = sprintf('%s: %s - %s', $codepoint, $primary, $codepoint->toUTF8());
            $output->writeln($message);
        }

        $output->writeln(sprintf('Memory peak: %.5f MB', memory_get_peak_usage() / 1048576));
        $output->writeln(sprintf('Took: %.5f seconds', microtime(true) - $start));
    }

    /**
     * @param Database $db
     * @param string $propertyType
     * @param string $searchBy
     * @return Collection|CodepointAssigned[]
     * @throws UnexpectedValueException
     */
    private function resolveCodepoints(Database $db, $propertyType, $searchBy)
    {
        switch ($propertyType) {
            case self::PROPERTY_BLOCK:
                $block = Block::fromValue($searchBy);
                return $db->getByBlock($block);
            case self::PROPERTY_CATEGORY:
                $category = GeneralCategory::fromValue($searchBy);
                return $db->getByCategory($category);
            case self::PROPERTY_SCRIPT:
                $script = Script::fromValue($searchBy);
                return $db->getByScript($script);
        }

        throw new UnexpectedValueException();
    }

    /**
     * @return InputDefinition
     */
    private function createInputDefinition()
    {
        $propertyType = new InputArgument(
            self::ARGUMENT_PROPERTY_TYPE,
            InputArgument::REQUIRED,
            'Property type. Choose from: block, category, script'
        );

        $searchBy = new InputArgument(
            self::ARGUMENT_SEARCH_BY,
            InputArgument::REQUIRED,
            'What to search by'
        );

        $repositoryNames = $this->getRepositoryNames();
        $namesList = implode(', ', $repositoryNames);

        $from = new InputOption(
            self::OPTION_FROM,
            null,
            InputOption::VALUE_OPTIONAL,
            sprintf('Repository from which codepoints should be resolved. Choose from: %s', $namesList),
            array_shift($repositoryNames)
        );

        return new InputDefinition([$propertyType, $searchBy, $from]);
    }
}