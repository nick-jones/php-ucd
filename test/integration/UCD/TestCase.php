<?php

namespace integration\UCD;

use UCD\Entity\Character;
use UCD\Entity\Character\Codepoint;
use UCD\Entity\Character\Properties;

use Hamcrest\MatcherAssert as ha;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
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
        $age = new Properties\Version(Properties\Version::V8_0);
        $primary = new Properties\Name\Assigned('Name');
        $names = new Properties\Names($primary);
        $block = new Properties\Block(Properties\Block::AEGEAN_NUMBERS);
        $cat = new Properties\GeneralCategory(Properties\GeneralCategory::LETTER_LOWERCASE);
        $combining = new Properties\Combining(Properties\Combining::ABOVE);
        $classing = new Properties\Bidirectionality\Classing(Properties\Bidirectionality\Classing::COMMON_SEPARATOR);
        $mirroring = new Properties\Bidirectionality\Mirroring(true);
        $bidi = new Properties\Bidirectionality($classing, $mirroring, true);
        $decompositionType = new Properties\DecompositionType(Properties\DecompositionType::CANONICAL);
        $decomp = new Properties\Decomposition\Assigned($decompositionType, []);
        $numericType = new Properties\Numericity\NumericType(Properties\Numericity\NumericType::NONE);
        $numericity = new Properties\Numericity\NonNumeric($numericType);
        $properties = new Character\Properties($age, $names, $block, $cat, $combining, $bidi, $decomp, $numericity);

        return new Character($codepoint, $properties);
    }
}