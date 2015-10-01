<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;

use PhpSpec\ObjectBehavior;

use UCD\Entity\Character;
use UCD\Entity\Character\Properties;

use UCD\Entity\Codepoint;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CharacterParser;

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

    public function it_parses_a_char_element_into_into_a_character_object()
    {
        $age = new Properties\General\Version(Properties\General\Version::V1_1);
        $primary = new Properties\General\Name\Unassigned();
        $nameV1 = new Properties\General\Name\Assigned('NULL');
        $names = new Properties\General\Names($primary, [], $nameV1);
        $block = new Properties\General\Block(Properties\General\Block::BASIC_LATIN);
        $cat = new Properties\General\GeneralCategory(Properties\General\GeneralCategory::OTHER_CONTROL);
        $combining = new Properties\Normalization\Combining(Properties\Normalization\Combining::NOT_REORDERED);
        $classing = new Properties\Bidirectionality\Classing(Properties\Bidirectionality\Classing::BOUNDARY_NEUTRAL);
        $mirroring = new Properties\Bidirectionality\Mirroring(false);
        $bidi = new Properties\Bidirectionality($classing, $mirroring, false);
        $dType = Properties\Normalization\DecompositionType::NONE;
        $decompositionType = new Properties\Normalization\DecompositionType($dType);
        $decomp = new Properties\Normalization\Decomposition\Void($decompositionType);
        $numericType = new Properties\Numericity\NumericType(Properties\Numericity\NumericType::NONE);
        $numericity = new Properties\Numericity\NonNumeric($numericType);
        $general = new Properties\General($names, $block, $age, $cat);
        $normalization = new Properties\Normalization($combining, $decomp);
        $joiningGroup = new Properties\Shaping\JoiningGroup(Properties\Shaping\JoiningGroup::NO_JOINING_GROUP);
        $joiningType = new Properties\Shaping\JoiningType(Properties\Shaping\JoiningType::NON_JOINING);
        $joining = new Properties\Shaping\Joining($joiningGroup, $joiningType, false);
        $shaping = new Properties\Shaping($joining);
        $properties = new Character\Properties($general, $numericity, $normalization, $bidi, $shaping);
        $character = new Character(Codepoint::fromInt(0), $properties);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML(self::XML_DATA);
        $element = $dom->getElementsByTagName('char')->item(0);

        $characters = $this->parseElement($element);
        $characters->shouldIterateLike([$character]);
    }
}