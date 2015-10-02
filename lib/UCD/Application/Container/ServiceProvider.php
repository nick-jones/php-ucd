<?php

namespace UCD\Application\Container;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

use Psr\Log\LogLevel;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

use UCD\Application\Console\Command\RepositoryTransferCommand;
use UCD\Application\Console\Command\SearchCommand;

use UCD\Infrastructure\Repository\CharacterRepository\DebugWritableRepository;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PHPSerializer;
use UCD\Infrastructure\Repository\CharacterRepository\NULLRepository;
use UCD\Infrastructure\Repository\CharacterRepository\PHPFileRepository;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\AggregateParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CharacterParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CodepointCountParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\NonCharacterParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\ReservedParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\SurrogateParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\StreamingElementReader;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\XMLReader;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $this->setupRepositories($pimple);
    }

    /**
     * @param Container $container
     */
    private function setupRepositories(Container $container)
    {
        $this->setupSymfony($container);
        $this->setupPHPFileRepository($container);
        $this->setupXMLRepository($container);
        $this->setupNULLRepository($container);
        $this->setupDisplayRepository($container);
        $this->setupCommands($container);
        $this->setupApplication($container);
    }

    /**
     * @param Container $container
     */
    private function setupSymfony(Container $container)
    {
        $this->addMany($container, [
            'symfony.input' => function () {
                return new ArgvInput();
            },
            'symfony.output' => function () {
                return new ConsoleOutput();
            }
        ]);
    }

    /**
     * @param Container $container
     */
    private function setupPHPFileRepository(Container $container)
    {
        $this->addMany($container, [
            'pfr.serializer' => function () {
                return new PHPSerializer();
            },
            'pfr.database_path' => function (Container $container) {
                return new \SplFileInfo($container['config.repository.php.database_path']);
            },
            'pfr.file_directory' => function (Container $container) {
                return new PHPFileDirectory($container['pfr.database_path']);
            },
            'repository.php' => function (Container $container) {
                return new PHPFileRepository($container['pfr.file_directory'], $container['pfr.serializer']);
            }
        ]);
    }

    /**
     * @param Container $container
     */
    private function setupXMLRepository(Container $container)
    {
        $this->addMany($container, [
            'xr.element_parser' => function () {
                return new AggregateParser([
                    'char' => new CharacterParser(),
                    'noncharacter' => new NonCharacterParser(),
                    'surrogate' => new SurrogateParser()
                ]);
            },
            'xr.codepoint_parser' => function () {
                return new CodepointCountParser();
            },
            'xr.xml_reader' => function (Container $container) {
                return new XMLReader($container['config.repository.xml.ucd_file_path']);
            },
            'xr.element_reader' => function (Container $container) {
                return new StreamingElementReader($container['xr.xml_reader']);
            },
            'repository.xml' => function (Container $container) {
                return new XMLRepository(
                    $container['xr.element_reader'],
                    $container['xr.element_parser'],
                    $container['xr.codepoint_parser']
                );
            }
        ]);
    }

    /**
     * @param Container $container
     */
    private function setupNULLRepository(Container $container)
    {
        $this->addMany($container, [
            'repository.null' => function () {
                return new NULLRepository();
            }
        ]);
    }

    /**
     * @param Container $container
     */
    private function setupDisplayRepository(Container $container)
    {
        $this->addMany($container, [
            'dr.logger' => function (Container $container) {
                return new ConsoleLogger($container['symfony.output'], [
                    LogLevel::INFO => OutputInterface::VERBOSITY_NORMAL,
                    LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL
                ]);
            },
            'repository.display' => function (Container $container) {
                return new DebugWritableRepository($container['repository.null'], $container['dr.logger']);
            }
        ]);
    }

    /**
     * @param Container $container
     */
    private function setupCommands(Container $container)
    {
        $this->addMany($container, [
            'command.repository_transfer' => function (Container $container) {
                return new RepositoryTransferCommand($container);
            },
            'command.search' => function (Container $container) {
                return new SearchCommand($container);
            }
        ]);
    }

    /**
     * @param Container $container
     */
    private function setupApplication(Container $container)
    {
        $this->addMany($container, [
            'application.ucd' => function (Container $container) {
                $application = new Application('Unicode Character Database', PHPUCD_VERSION);
                $application->add($container['command.repository_transfer']);
                $application->add($container['command.search']);
                $definition = $application->getDefinition();
                $option = new InputOption('config', 'c', InputOption::VALUE_OPTIONAL, 'Configuration file', null);
                $definition->addOption($option);

                return $application;
            }
        ]);
    }

    /**
     * @param Container $container
     * @param array $services
     */
    private function addMany(Container $container, array $services)
    {
        foreach ($services as $id => $definition) {
            $container[$id] = $definition;
        }
    }
}