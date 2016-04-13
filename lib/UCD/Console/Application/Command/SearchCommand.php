<?php

namespace UCD\Console\Application\Command;

use SebastianBergmann\Exporter\Exporter;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use UCD\Database;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Codepoint;
use UCD\Exception\InvalidArgumentException;
use UCD\Unicode\TransformationFormat;

class SearchCommand extends RepositoryUtilisingCommand
{
    const COMMAND_NAME = 'search';
    const ARGUMENT_CODEPOINT = 'codepoint';
    const OPTION_FROM = 'from';
    const OPTION_ENCODING = 'enc';
    const ENCODING_DECIMAL = 'dec';
    const ENCODING_HEXADECIMAL = 'hex';
    const ENCODING_UTF8 = 'utf8';

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
        $encoding = $input->getOption(self::OPTION_ENCODING);
        $codepointValue = $input->getArgument(self::ARGUMENT_CODEPOINT);
        $codepoint = $this->valueToCodepoint($codepointValue, $encoding);
        $from = $input->getOption(self::OPTION_FROM);
        $repository = $this->getRepositoryByName($from);
        $db = new Database($repository);
        $exporter = new Exporter();

        try {
            $character = $db->getCharacterByCodepoint($codepoint);
        } catch (CharacterNotFoundException $e) {
            $output->writeln('<error>Character Not Found</error>');
            return 1;
        }

        $output->writeln('<info>Character Found</info>');
        $output->writeln(sprintf('Export: %s', $exporter->export($character)));
        $output->writeln(sprintf('UTF-8: %s', $codepoint->toUTF8()));
        $output->writeln(sprintf('Memory peak: %.5f MB', memory_get_peak_usage() / 1048576));
        $output->writeln(sprintf('Took: %.5f seconds', microtime(true) - $start));

        return 0;
    }

    /**
     * @param string $value
     * @param string $encoding
     * @return Codepoint
     * @throws InvalidArgumentException
     */
    private function valueToCodepoint($value, $encoding)
    {
        if ($encoding === self::ENCODING_DECIMAL) {
            return Codepoint::fromInt((int)$value);
        } elseif ($encoding === self::ENCODING_HEXADECIMAL) {
            return Codepoint::fromHex($value);
        } elseif ($encoding === self::ENCODING_UTF8) {
            return Codepoint::fromUTF8($value);
        }

        throw new InvalidArgumentException(sprintf('Unknown encoding: %s', $encoding));
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

        $encoding = new InputOption(
            self::OPTION_ENCODING,
            null,
            InputOption::VALUE_OPTIONAL,
            'Encoding of the supplied value. Choose from: dec, hex, utf8',
            self::ENCODING_HEXADECIMAL
        );

        return new InputDefinition([$codepoint, $from, $encoding]);
    }
}