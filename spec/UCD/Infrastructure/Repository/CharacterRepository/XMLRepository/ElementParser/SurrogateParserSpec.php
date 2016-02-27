<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use PhpSpec\ObjectBehavior;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties;
use UCD\Unicode\Codepoint;
use UCD\Unicode\NonCharacter;

use UCD\Unicode\Surrogate;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\GeneralParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\SurrogateParser;

/**
 * @mixin SurrogateParser
 */
class SurrogateParserSpec extends ObjectBehavior
{
    const XML_DATA = <<<XML
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
        <surrogate cp="D800" age="2.0" na="" JSN="" gc="Cs" ccc="0" dt="none" dm="#" nt="None" nv="NaN" bc="L" bpt="n"
               bpb="#" Bidi_M="N" bmg="" suc="#" slc="#" stc="#" uc="#" lc="#" tc="#" scf="#" cf="#" jt="U"
               jg="No_Joining_Group" ea="N" lb="SG" sc="Zzzz" scx="Zzzz" Dash="N" WSpace="N" Hyphen="N" QMark="N"
               Radical="N" Ideo="N" UIdeo="N" IDSB="N" IDST="N" hst="NA" DI="N" ODI="N" Alpha="N" OAlpha="N"
               Upper="N" OUpper="N" Lower="N" OLower="N" Math="N" OMath="N" Hex="N" AHex="N" NChar="N" VS="N"
               Bidi_C="N" Join_C="N" Gr_Base="N" Gr_Ext="N" OGr_Ext="N" Gr_Link="N" STerm="N" Ext="N" Term="N"
               Dia="N" Dep="N" IDS="N" OIDS="N" XIDS="N" IDC="N" OIDC="N" XIDC="N" SD="N" LOE="N" Pat_WS="N"
               Pat_Syn="N" GCB="CN" WB="XX" SB="XX" CE="N" Comp_Ex="N" NFC_QC="Y" NFD_QC="Y" NFKC_QC="Y" NFKD_QC="Y"
               XO_NFC="N" XO_NFD="N" XO_NFKC="N" XO_NFKD="N" FC_NFKC="#" CI="N" Cased="N" CWCF="N" CWCM="N"
               CWKCF="N" CWL="N" CWT="N" CWU="N" NFKC_CF="#" InSC="Other" InPC="NA" blk="High_Surrogates" isc=""
               na1=""/>
   </repertoire>
</ucd>
XML;

    public function let()
    {
        $this->beConstructedWith(new GeneralParser());
    }

    public function it_parses_a_noncharacter_element_into_into_a_noncharacter_object()
    {
        $age = new Properties\General\Version(Properties\General\Version::V2_0);
        $primary = new Properties\General\Name\Unassigned();
        $names = new Properties\General\Names($primary, [], $primary);
        $block = new Properties\General\Block(Properties\General\Block::HIGH_SURROGATES);
        $cat = new Properties\General\GeneralCategory(Properties\General\GeneralCategory::OTHER_SURROGATE);
        $script = new Properties\General\Script(Properties\General\Script::UNKNOWN);
        $properties = new Properties\General($names, $block, $age, $cat, $script);
        $codepoint = Codepoint::fromInt(55296);
        $character = new Surrogate($codepoint, $properties);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML(self::XML_DATA);
        $element = $dom->getElementsByTagName('surrogate')->item(0);

        $this->parseElement($element, $codepoint)
            ->shouldBeLike($character);
    }
}