<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties;

use UCD\Unicode\Character\Properties\Bidirectionality;
use UCD\Unicode\Character\Properties\Bidirectionality\Bracket;
use UCD\Unicode\Character\Properties\Bidirectionality\BracketBidirectionality;
use UCD\Unicode\Character\Properties\Bidirectionality\Classing;
use UCD\Unicode\Character\Properties\Bidirectionality\Mirroring;
use UCD\Unicode\Codepoint;

class BidirectionalityParser extends BaseParser
{
    const BACKET_TYPE_NONE = 'n';
    const BRACKET_TYPE_OPEN = 'o';

    const ATTR_BIDIRECTIONALITY_CLASS = 'bc';
    const ATTR_MIRRORED = 'Bidi_M';
    const ATTR_MIRROR_GLYPH = 'bmg';
    const ATTR_BIDIRECTIONALITY_CONTROL = 'Bidi_C';
    const ATTR_PAIRED_BRACKET_TYPE = 'bpt';
    const ATTR_PAIRED_BRACKET = 'bpb';

    /**
     * @return mixed
     */
    protected function parse()
    {
        $class = $this->parseClassing();
        $mirroring = $this->parseMirroring();
        $isControl = $this->getBoolAttribute(self::ATTR_BIDIRECTIONALITY_CONTROL);
        $bracketType = $this->getAttribute(self::ATTR_PAIRED_BRACKET_TYPE);

        if ($bracketType === self::BACKET_TYPE_NONE) {
            return new Bidirectionality($class, $mirroring, $isControl);
        }

        $pairedWith = Codepoint::fromHex($this->getAttribute(self::ATTR_PAIRED_BRACKET));

        $bracket = ($bracketType === self::BRACKET_TYPE_OPEN)
            ? Bracket::createOpen($pairedWith)
            : Bracket::createClose($pairedWith);

        return new BracketBidirectionality($class, $mirroring, $isControl, $bracket);
    }

    /**
     * @return Classing
     */
    private function parseClassing()
    {
        return new Classing($this->getAttribute(self::ATTR_BIDIRECTIONALITY_CLASS));
    }

    /**
     * @return Mirroring
     */
    private function parseMirroring()
    {
        $isMirrored = $this->getBoolAttribute(self::ATTR_MIRRORED);
        $mirroredByValue = $this->getOptionalAttribute(self::ATTR_MIRROR_GLYPH);

        $mirroredBy = ($mirroredByValue !== null)
            ? Codepoint::fromHex($mirroredByValue)
            : null;

        return new Mirroring($isMirrored, $mirroredBy);
    }
}