<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use UCD\Entity\Character;
use UCD\Entity\Character\Properties;
use UCD\Entity\Codepoint;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\BidirectionalityParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\GeneralParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\NormalizationParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\NumericityParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\ShapingParser;

class CharacterParser implements CodepointAwareParser
{
    /**
     * @var GeneralParser
     */
    private $generalParser;

    /**
     * @var NormalizationParser
     */
    private $normalizationParser;

    /**
     * @var NumericityParser
     */
    private $numericityParser;

    /**
     * @var BidirectionalityParser
     */
    private $bidirectionalityParser;

    /**
     * @var ShapingParser
     */
    private $shapingParser;

    /**
     * @param GeneralParser $generalParser
     * @param NormalizationParser $normalizationParser
     * @param NumericityParser $numericityParser
     * @param BidirectionalityParser $bidirectionalityParser
     * @param ShapingParser $shapingParser
     */
    public function __construct(
        GeneralParser $generalParser,
        NormalizationParser $normalizationParser,
        NumericityParser $numericityParser,
        BidirectionalityParser $bidirectionalityParser,
        ShapingParser $shapingParser
    ) {
        $this->generalParser = $generalParser;
        $this->normalizationParser = $normalizationParser;
        $this->numericityParser = $numericityParser;
        $this->bidirectionalityParser = $bidirectionalityParser;
        $this->shapingParser = $shapingParser;
    }

    /**
     * @param \DOMElement $element
     * @param Codepoint $codepoint
     * @return Character
     */
    public function parseElement(\DOMElement $element, Codepoint $codepoint = null)
    {
        $general = $this->generalParser->parseElement($element, $codepoint);
        $normalization = $this->normalizationParser->parseElement($element, $codepoint);
        $numericity = $this->numericityParser->parseElement($element, $codepoint);
        $bidirectionality = $this->bidirectionalityParser->parseElement($element, $codepoint);
        $shaping = $this->shapingParser->parseElement($element, $codepoint);
        $properties = new Properties($general, $numericity, $normalization, $bidirectionality, $shaping);

        return new Character($codepoint, $properties);
    }
}