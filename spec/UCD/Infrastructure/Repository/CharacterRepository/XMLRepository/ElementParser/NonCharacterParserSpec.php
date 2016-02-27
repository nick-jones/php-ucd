<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use PhpSpec\ObjectBehavior;

use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties;
use UCD\Unicode\Codepoint;
use UCD\Unicode\NonCharacter;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\NonCharacterParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties\GeneralParser;

/**
 * @mixin NonCharacterParser
 */
class NonCharacterParserSpec extends ObjectBehavior
{
    const XML_DATA = <<<XML
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
        <noncharacter cp="FDD0" age="3.1" na="" JSN="" gc="Cn" ccc="0" dt="none" dm="#" nt="None" nv="NaN" bc="BN"
              bpt="n" bpb="#" Bidi_M="N" bmg="" suc="#" slc="#" stc="#" uc="#" lc="#" tc="#" scf="#" cf="#"
              jt="U" jg="No_Joining_Group" ea="N" lb="XX" sc="Zzzz" scx="Zzzz" Dash="N" WSpace="N" Hyphen="N"
              QMark="N" Radical="N" Ideo="N" UIdeo="N" IDSB="N" IDST="N" hst="NA" DI="N" ODI="N" Alpha="N"
              OAlpha="N" Upper="N" OUpper="N" Lower="N" OLower="N" Math="N" OMath="N" Hex="N" AHex="N" NChar="Y"
              VS="N" Bidi_C="N" Join_C="N" Gr_Base="N" Gr_Ext="N" OGr_Ext="N" Gr_Link="N" STerm="N" Ext="N"
              Term="N" Dia="N" Dep="N" IDS="N" OIDS="N" XIDS="N" IDC="N" OIDC="N" XIDC="N" SD="N" LOE="N"
              Pat_WS="N" Pat_Syn="N" GCB="XX" WB="XX" SB="XX" CE="N" Comp_Ex="N" NFC_QC="Y" NFD_QC="Y"
              NFKC_QC="Y" NFKD_QC="Y" XO_NFC="N" XO_NFD="N" XO_NFKC="N" XO_NFKD="N" FC_NFKC="#" CI="N"
              Cased="N" CWCF="N" CWCM="N" CWKCF="N" CWL="N" CWT="N" CWU="N" NFKC_CF="#" InSC="Other" InPC="NA"
              blk="Arabic_PF_A" isc="" na1=""/>
   </repertoire>
</ucd>
XML;

    public function let()
    {
        $this->beConstructedWith(new GeneralParser());
    }

    public function it_parses_a_noncharacter_element_into_into_a_noncharacter_object()
    {
        $age = new Properties\General\Version(Properties\General\Version::V3_1);
        $primary = new Properties\General\Name\Unassigned();
        $names = new Properties\General\Names($primary, [], $primary);
        $block = new Properties\General\Block(Properties\General\Block::ARABIC_PRESENTATION_FORMS_A);
        $cat = new Properties\General\GeneralCategory(Properties\General\GeneralCategory::OTHER_NOT_ASSIGNED);
        $script = new Properties\General\Script(Properties\General\Script::UNKNOWN);
        $properties = new Properties\General($names, $block, $age, $cat, $script);
        $codepoint = Codepoint::fromInt(64976);
        $character = new NonCharacter($codepoint, $properties);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML(self::XML_DATA);
        $element = $dom->getElementsByTagName('noncharacter')->item(0);

        $this->parseElement($element, $codepoint)
            ->shouldBeLike($character);
    }
}