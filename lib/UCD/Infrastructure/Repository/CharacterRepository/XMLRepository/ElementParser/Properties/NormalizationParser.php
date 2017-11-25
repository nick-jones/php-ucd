<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties;

use UCD\Unicode\Character\Properties\Normalization;
use UCD\Unicode\Character\Properties\Normalization\Combining;
use UCD\Unicode\Character\Properties\Normalization\Decomposition;
use UCD\Unicode\Character\Properties\Normalization\Decomposition\Assigned;
use UCD\Unicode\Character\Properties\Normalization\Decomposition\Nil;
use UCD\Unicode\Character\Properties\Normalization\DecompositionType;

class NormalizationParser extends BaseParser
{
    const ATTR_CANONICAL_COMBINING_CLASS = 'ccc';
    const ATTR_DECOMPOSITION_TYPE = 'dt';
    const ATTR_DECOMPOSITION_MAPPING = 'dm';

    /**
     * @return Normalization
     */
    protected function parse()
    {
        $combining = $this->parseCombiningClass();
        $decomposition = $this->parseDecomposition();

        return new Normalization($combining, $decomposition);
    }

    /**
     * @return Decomposition
     */
    private function parseDecomposition()
    {
        $decompositionType = $this->parseDecompositionType();
        $mapping = $this->getAttribute(self::ATTR_DECOMPOSITION_MAPPING);
        $placeholders = $this->parsePlaceholders($mapping, $this->codepoint);
        $decompositionMap = $this->parseCodepointList($placeholders);
        $count = count($decompositionMap);
        $isNil = $count === 0 || ($count === 1 && $decompositionMap[0]->equals($this->codepoint));

        if ($isNil) {
            return new Nil($decompositionType);
        }

        return new Assigned($decompositionType, $decompositionMap);
    }

    /**
     * @return DecompositionType
     */
    private function parseDecompositionType()
    {
        return new DecompositionType($this->getAttribute(self::ATTR_DECOMPOSITION_TYPE));
    }

    /**
     * @return Combining
     */
    private function parseCombiningClass()
    {
        return new Combining((int)$this->getAttribute(self::ATTR_CANONICAL_COMBINING_CLASS));
    }
}