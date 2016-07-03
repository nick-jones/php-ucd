<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use PhpSpec\ObjectBehavior;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\LetterCaseParser;
use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties;

use UCD\Unicode\Codepoint;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CharacterParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\BidirectionalityParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\GeneralParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\NormalizationParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\NumericityParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\ShapingParser;

/**
 * @mixin CharacterParser
 */
class CharacterParserSpec extends ObjectBehavior
{
    const XML_DATA = <<<XML
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
      <char cp="0000" age="1.1" na="" JSN="" gc="Cc" ccc="0" dt="none" dm="#" nt="None" nv="NaN" bc="BN" bpt="n" bpb="#"
            Bidi_M="N" bmg="" suc="#" slc="#" stc="#" uc="#" lc="#" tc="#" scf="#" cf="#" jt="U" jg="No_Joining_Group"
            ea="N" lb="CM" sc="Zyyy" scx="Zyyy" Dash="N" WSpace="N" Hyphen="N" QMark="N" Radical="N" Ideo="N" UIdeo="N"
            IDSB="N" IDST="N" hst="NA" DI="N" ODI="N" Alpha="N" OAlpha="N" Upper="N" OUpper="N" Lower="N" OLower="N"
            Math="N" OMath="N" Hex="N" AHex="N" NChar="N" VS="N" Bidi_C="N" Join_C="N" Gr_Base="N" Gr_Ext="N"
            OGr_Ext="N" Gr_Link="N" STerm="N" Ext="N" Term="N" Dia="N" Dep="N" IDS="N" OIDS="N" XIDS="N" IDC="N"
            OIDC="N" XIDC="N" SD="N" LOE="N" Pat_WS="N" Pat_Syn="N" GCB="CN" WB="XX" SB="XX" CE="N" Comp_Ex="N"
            NFC_QC="Y" NFD_QC="Y" NFKC_QC="Y" NFKD_QC="Y" XO_NFC="N" XO_NFD="N" XO_NFKC="N" XO_NFKD="N" FC_NFKC="#"
            CI="N" Cased="N" CWCF="N" CWCM="N" CWKCF="N" CWL="N" CWT="N" CWU="N" NFKC_CF="#" InSC="Other" InPC="NA"
            blk="ASCII" isc="" na1="NULL">
         <name-alias alias="NUL" type="abbreviation"/>
         <name-alias alias="NULL" type="control"/>
      </char>
   </repertoire>
</ucd>
XML;

    public function let()
    {
        $this->beConstructedWith(
            new GeneralParser(),
            new LetterCaseParser(),
            new NormalizationParser(),
            new NumericityParser(),
            new BidirectionalityParser(),
            new ShapingParser()
        );
    }

    public function it_parses_a_char_element_into_into_a_character_object()
    {
        $codepoint = Codepoint::fromInt(0);
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML(self::XML_DATA);
        $element = $dom->getElementsByTagName('char')->item(0);

        $this->parseElement($element, $codepoint)
            ->shouldHaveType(Character::class);
    }
}