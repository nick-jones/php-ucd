<?php

namespace integration\UCD;

use UCD\Application\Container\ConfigurationProvider;
use UCD\Application\Container\ServiceProvider;

use UCD\Entity\Character;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Properties;

use Hamcrest\MatcherAssert as ha;
use Pimple\Container;
use VirtualFileSystem\FileSystem;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FileSystem
     */
    protected $fs;

    /**
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->fs = new FileSystem();
        $this->container = new Container();
        $this->container->register(new ServiceProvider());
        $this->container->register(new ConfigurationProvider());

        parent::__construct($name, $data, $dataName);
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
     * @return Character
     */
    protected function buildCharacterWithCodepoint(Codepoint $codepoint)
    {
        $age = new Properties\General\Version(Properties\General\Version::V8_0);
        $primary = new Properties\General\Name\Assigned('Name');
        $names = new Properties\General\Names($primary);
        $block = new Properties\General\Block(Properties\General\Block::AEGEAN_NUMBERS);
        $cat = new Properties\General\GeneralCategory(Properties\General\GeneralCategory::LETTER_LOWERCASE);
        $combining = new Properties\Normalization\Combining(Properties\Normalization\Combining::ABOVE);
        $classing = new Properties\Bidirectionality\Classing(Properties\Bidirectionality\Classing::COMMON_SEPARATOR);
        $mirroring = new Properties\Bidirectionality\Mirroring(true);
        $bidi = new Properties\Bidirectionality($classing, $mirroring, true);
        $dType = Properties\Normalization\DecompositionType::CANONICAL;
        $decompositionType = new Properties\Normalization\DecompositionType($dType);
        $decomp = new Properties\Normalization\Decomposition\Assigned($decompositionType, []);
        $numericType = new Properties\Numericity\NumericType(Properties\Numericity\NumericType::NONE);
        $numericity = new Properties\Numericity\NonNumeric($numericType);
        $general = new Properties\General($names, $block, $age, $cat);
        $normalization = new Properties\Normalization($combining, $decomp);
        $joiningGroup = new Properties\Shaping\JoiningGroup(Properties\Shaping\JoiningGroup::NO_JOINING_GROUP);
        $joiningType = new Properties\Shaping\JoiningType(Properties\Shaping\JoiningType::NON_JOINING);
        $joining = new Properties\Shaping\Joining($joiningGroup, $joiningType, false);
        $shaping = new Properties\Shaping($joining);
        $properties = new Character\Properties($general, $numericity, $normalization, $bidi, $shaping);

        return new Character($codepoint, $properties);
    }
}