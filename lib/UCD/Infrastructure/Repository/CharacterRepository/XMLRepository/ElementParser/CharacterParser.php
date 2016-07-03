<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\LetterCaseParser;
use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties;
use UCD\Unicode\Codepoint;

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
     * @var LetterCaseParser
     */
    private $letterCaseParser;

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
     * @param LetterCaseParser $letterCaseParser
     * @param NormalizationParser $normalizationParser
     * @param NumericityParser $numericityParser
     * @param BidirectionalityParser $bidirectionalityParser
     * @param ShapingParser $shapingParser
     */
    public function __construct(
        GeneralParser $generalParser,
        LetterCaseParser $letterCaseParser,
        NormalizationParser $normalizationParser,
        NumericityParser $numericityParser,
        BidirectionalityParser $bidirectionalityParser,
        ShapingParser $shapingParser
    ) {
        $this->generalParser = $generalParser;
        $this->letterCaseParser = $letterCaseParser;
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
    public function parseElement(\DOMElement $element, Codepoint $codepoint)
    {
        $general = $this->generalParser->parseElement($element, $codepoint);
        $case = $this->letterCaseParser->parseElement($element, $codepoint);
        $normalization = $this->normalizationParser->parseElement($element, $codepoint);
        $numericity = $this->numericityParser->parseElement($element, $codepoint);
        $bidirectionality = $this->bidirectionalityParser->parseElement($element, $codepoint);
        $shaping = $this->shapingParser->parseElement($element, $codepoint);
        $properties = new Properties($general, $case, $numericity, $normalization, $bidirectionality, $shaping);

        return new Character($codepoint, $properties);
    }
}