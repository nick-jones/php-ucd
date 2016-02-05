<?php

namespace integration\UCD;

use UCD\Console\Application\Container\ApplicationServiceProvider;
use UCD\Console\Application\Container\ConfigurationProvider;
use UCD\Console\Application\Container\RepositoryServiceProvider;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Character\Properties;
use UCD\Console\Application\Container;

use Hamcrest\MatcherAssert as ha;
use VirtualFileSystem\FileSystem;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FileSystem
     */
    protected $fs;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->fs = new FileSystem();
        $this->container = new Container();

        parent::__construct($name, $data, $dataName);
    }

    /**
     * The providers are not loaded by default.
     */
    protected function registerContainerProviders()
    {
        $this->container->register(new ApplicationServiceProvider());
        $this->container->register(new RepositoryServiceProvider());
    }

    /**
     * Adds Hamcrest assertion counts to PHPUnit
     */
    public function runBare()
    {
        $e = null;
        ha::resetCount();

        try {
            parent::runBare();
        } catch (\Exception $e) { }

        $this->addToAssertionCount(ha::getCount());

        if ($e !== null) {
            throw $e;
        }
    }

    /**
     * @param Codepoint $codepoint
     * @param Block $block
     * @return Character
     */
    protected function buildCharacterWithCodepoint(Codepoint $codepoint, Block $block = null)
    {
        $age = new Properties\General\Version(Properties\General\Version::V8_0);
        $primary = new Properties\General\Name\Assigned('Name');
        $names = new Properties\General\Names($primary);
        $cat = new Properties\General\GeneralCategory(Properties\General\GeneralCategory::OTHER_CONTROL);
        $combining = new Properties\Normalization\Combining(Properties\Normalization\Combining::ABOVE);
        $classing = new Properties\Bidirectionality\Classing(Properties\Bidirectionality\Classing::COMMON_SEPARATOR);
        $mirroring = new Properties\Bidirectionality\Mirroring(true);
        $bidi = new Properties\Bidirectionality($classing, $mirroring, true);
        $dType = Properties\Normalization\DecompositionType::CANONICAL;
        $decompositionType = new Properties\Normalization\DecompositionType($dType);
        $decomp = new Properties\Normalization\Decomposition\Assigned($decompositionType, []);
        $numericType = new Properties\Numericity\NumericType(Properties\Numericity\NumericType::NONE);
        $numericity = new Properties\Numericity\NonNumeric($numericType);
        $general = new Properties\General($names, $block ?: new Block(Block::BASIC_LATIN), $age, $cat);
        $normalization = new Properties\Normalization($combining, $decomp);
        $joiningGroup = new Properties\Shaping\JoiningGroup(Properties\Shaping\JoiningGroup::NO_JOINING_GROUP);
        $joiningType = new Properties\Shaping\JoiningType(Properties\Shaping\JoiningType::NON_JOINING);
        $joining = new Properties\Shaping\Joining($joiningGroup, $joiningType, false);
        $shaping = new Properties\Shaping($joining);
        $properties = new Character\Properties($general, $numericity, $normalization, $bidi, $shaping);

        return new Character($codepoint, $properties);
    }
}