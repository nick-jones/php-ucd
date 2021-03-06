<?php

namespace integration\UCD;

use UCD\Console\Application\Container\ApplicationServiceProvider;
use UCD\Console\Application\Container\RepositoryServiceProvider;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Character\Properties\General\Script;
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
     * @param Codepoint $cp
     * @param Block $block
     * @param GeneralCategory $cat
     * @param Script $script
     * @return Character
     */
    protected function buildCharacterWithCodepoint(
        Codepoint $cp,
        Block $block = null,
        GeneralCategory $cat = null,
        Script $script = null
    ) {
        $general = $this->buildGeneralProperties($block, $cat, $script);
        $letterCase = $this->buildLetterCase($cp);
        $numericity = $this->buildNumericity();
        $normalization = $this->buildNormalization();
        $bidi = $this->buildBidirectionality();
        $shaping = $this->buildShaping();
        $properties = new Character\Properties($general, $letterCase, $numericity, $normalization, $bidi, $shaping);

        return new Character($cp, $properties);
    }

    protected function buildGeneralProperties(Block $block = null, GeneralCategory $cat = null, Script $script = null)
    {
        $cat = $cat ?: new GeneralCategory(GeneralCategory::OTHER_CONTROL);
        $script = $script ?: new Script(Script::COMMON);
        $age = new Properties\General\Version(Properties\General\Version::V8_0);
        $primary = new Properties\General\Name\Assigned('Name');
        $names = new Properties\General\Names($primary);

        return new Properties\General($names, $block ?: new Block(Block::BASIC_LATIN), $age, $cat, $script);
    }

    protected function buildLetterCase(Codepoint $cp)
    {
        $mapping = new Properties\LetterCase\Mapping($cp, [$cp]);
        $mappings = new Properties\LetterCase\Mappings($mapping, $mapping, $mapping, $mapping);

        return new Properties\LetterCase($mappings);
    }

    protected function buildNumericity()
    {
        $numericType = new Properties\Numericity\NumericType(Properties\Numericity\NumericType::NONE);

        return new Properties\Numericity\NonNumeric($numericType);
    }

    protected function buildNormalization()
    {
        $combining = new Properties\Normalization\Combining(Properties\Normalization\Combining::ABOVE);
        $dType = Properties\Normalization\DecompositionType::CANONICAL;
        $decompositionType = new Properties\Normalization\DecompositionType($dType);
        $decomp = new Properties\Normalization\Decomposition\Assigned($decompositionType, []);

        return new Properties\Normalization($combining, $decomp);
    }

    protected function buildBidirectionality()
    {
        $classing = new Properties\Bidirectionality\Classing(Properties\Bidirectionality\Classing::COMMON_SEPARATOR);
        $mirroring = new Properties\Bidirectionality\Mirroring(true);

        return new Properties\Bidirectionality($classing, $mirroring, true);
    }

    protected function buildShaping()
    {
        $joiningGroup = new Properties\Shaping\JoiningGroup(Properties\Shaping\JoiningGroup::NO_JOINING_GROUP);
        $joiningType = new Properties\Shaping\JoiningType(Properties\Shaping\JoiningType::NON_JOINING);
        $joining = new Properties\Shaping\Joining($joiningGroup, $joiningType, false);

        return new Properties\Shaping($joining);
    }
}