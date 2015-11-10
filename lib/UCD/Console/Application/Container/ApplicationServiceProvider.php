<?php

namespace UCD\Console\Application\Container;

use Pimple\Container;
use Psr\Log\LogLevel;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

use UCD\Console\Application\Command\RepositoryTransferCommand;
use UCD\Console\Application\Command\SearchCommand;

class ApplicationServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(Container $pimple)
    {
        $this->setupLogger($pimple);
        $this->setupSymfony($pimple);
        $this->setupCommands($pimple);
        $this->setupApplication($pimple);
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
    private function setupLogger(Container $container)
    {
        $this->addMany($container, [
            'logger.psr' => function (Container $container) {
                return new ConsoleLogger($container['symfony.output'], [
                    LogLevel::INFO => OutputInterface::VERBOSITY_NORMAL,
                    LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL
                ]);
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
}