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
        $properties = new Character\Properties($general, $numericity, $normalization, $bidi);

        return new Character($codepoint, $properties);
    }
}