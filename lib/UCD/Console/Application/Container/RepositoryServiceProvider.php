<?php

namespace UCD\Console\Application\Container;

use Pimple\Container;

use UCD\Infrastructure\Repository\CharacterRepository\DebugWritableRepository;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\KeyGenerator\BlockKeyGenerator;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Property;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyAggregators;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFile\PHPPropertyFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\RangeFile\PHPRangeFileDirectory;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Serializer\PHPSerializer;
use UCD\Infrastructure\Repository\CharacterRepository\InMemoryRepository;
use UCD\Infrastructure\Repository\CharacterRepository\NULLRepository;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\CodepointElementReader\StreamingReader;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CharacterParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CodepointAssignedParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CodepointCountParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\NonCharacterParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\BidirectionalityParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\GeneralParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\NormalizationParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\NumericityParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\ShapingParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\SurrogateParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\XMLReader;

use UCD\Unicode\Codepoint\Aggregator\Factory;
use UCD\Unicode\Codepoint\AggregatorRelay;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
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
        $this->setupPHPFileRepository($container);
        $this->setupXMLRepositoryIfAvailable($container);
        $this->setupNULLRepository($container);
        $this->setupDisplayRepository($container);
        $this->setupInMemoryRepository($container);
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
                return new \SplFileInfo($container[ConfigurationProvider::CONFIG_KEY_DB_PATH]);
            },
            'pfr.properties_path' => function (Container $container) {
                return new \SplFileInfo($container[ConfigurationProvider::CONFIG_KEY_PROPS_PATH]);
            },
            'pfr.characters_directory' => function (Container $container) {
                return PHPRangeFileDirectory::fromPath($container['pfr.database_path']);
            },
            'pfr.properties_directory' => function (Container $container) {
                return PHPPropertyFileDirectory::fromPath($container['pfr.properties_path']);
            },
            'pft.property_aggregators.block' => function () {
                return new AggregatorRelay(new BlockKeyGenerator(), new Factory());
            },
            'pfr.property_aggregators' => function (Container $container) {
                $aggregators = new PropertyAggregators();
                $block = $container['pft.property_aggregators.block'];
                $aggregators->registerAggregatorRelay(Property::ofType(Property::BLOCK), $block);
                return $aggregators;
            },
            'repository.php' => function (Container $container) {
                return new FileRepository(
                    $container['pfr.characters_directory'],
                    $container['pfr.properties_directory'],
                    $container['pfr.property_aggregators'],
                    $container['pfr.serializer']
                );
            }
        ]);
    }

    /**
     * @param Container $container
     */
    private function setupXMLRepositoryIfAvailable(Container $container)
    {
        $available = isset($container[ConfigurationProvider::CONFIG_KEY_XML_PATH])
            && is_readable($container[ConfigurationProvider::CONFIG_KEY_XML_PATH]);

        if ($available) {
            $this->setupXMLRepository($container);
        }
    }

    /**
     * @param Container $container
     */
    private function setupXMLRepository(Container $container)
    {
        $this->addMany($container, [
            'xr.element_parser.properties.general' => function () {
                return new GeneralParser();
            },
            'xr.element_parser.properties.normalization' => function () {
                return new NormalizationParser();
            },
            'xr.element_parser.properties.numericity' => function () {
                return new NumericityParser();
            },
            'xr.element_parser.properties.bidirectionality' => function () {
                return new BidirectionalityParser();
            },
            'xr.element_parser.properties.shaping' => function () {
                return new ShapingParser();
            },
            'xr.element_parser.character' => function (Container $container) {
                return new CharacterParser(
                    $container['xr.element_parser.properties.general'],
                    $container['xr.element_parser.properties.normalization'],
                    $container['xr.element_parser.properties.numericity'],
                    $container['xr.element_parser.properties.bidirectionality'],
                    $container['xr.element_parser.properties.shaping']
                );
            },
            'xr.element_parser.non_character' => function (Container $container) {
                return new NonCharacterParser($container['xr.element_parser.properties.general']);
            },
            'xr.element_parser.surrogate' => function (Container $container) {
                return new SurrogateParser($container['xr.element_parser.properties.general']);
            },
            'xr.element_parser' => function (Container $container) {
                return new CodepointAssignedParser(
                    $container['xr.element_parser.character'],
                    $container['xr.element_parser.non_character'],
                    $container['xr.element_parser.surrogate']
                );
            },
            'xr.codepoint_parser' => function () {
                return new CodepointCountParser();
            },
            'xr.xml_reader' => function (Container $container) {
                return new XMLReader($container[ConfigurationProvider::CONFIG_KEY_XML_PATH]);
            },
            'xr.element_reader' => function (Container $container) {
                return new StreamingReader($container['xr.xml_reader']);
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
            'repository.display' => function (Container $container) {
                return new DebugWritableRepository($container['repository.null'], $container['logger.psr']);
            }
        ]);
    }

    /**
     * @param Container $container
     */
    private function setupInMemoryRepository(Container $container)
    {
        $this->addMany($container, [
            'repository.in-memory' => function () {
                return new InMemoryRepository();
            }
        ]);
    }
}