<?php

namespace UCD\Unicode\Character\Properties\General;

use UCD\Unicode\Character\Properties\Enumeration;

class Block extends Enumeration
{
    const ADLAM = 'Adlam';
    const AEGEAN_NUMBERS = 'Aegean_Numbers';
    const AHOM = 'Ahom';
    const ALCHEMICAL_SYMBOLS = 'Alchemical';
    const ALPHABETIC_PRESENTATION_FORMS = 'Alphabetic_PF';
    const ANATOLIAN_HIEROGLYPHS = 'Anatolian_Hieroglyphs';
    const ANCIENT_GREEK_MUSICAL_NOTATION = 'Ancient_Greek_Music';
    const ANCIENT_GREEK_NUMBERS = 'Ancient_Greek_Numbers';
    const ANCIENT_SYMBOLS = 'Ancient_Symbols';
    const ARABIC = 'Arabic';
    const ARABIC_EXTENDED_A = 'Arabic_Ext_A';
    const ARABIC_MATHEMATICAL_ALPHABETIC_SYMBOLS = 'Arabic_Math';
    const ARABIC_PRESENTATION_FORMS_A = 'Arabic_PF_A';
    const ARABIC_PRESENTATION_FORMS_B = 'Arabic_PF_B';
    const ARABIC_SUPPLEMENT = 'Arabic_Sup';
    const ARMENIAN = 'Armenian';
    const ARROWS = 'Arrows';
    const BASIC_LATIN = 'ASCII';
    const AVESTAN = 'Avestan';
    const BALINESE = 'Balinese';
    const BAMUM = 'Bamum';
    const BAMUM_SUPPLEMENT = 'Bamum_Sup';
    const BASSA_VAH = 'Bassa_Vah';
    const BATAK = 'Batak';
    const BENGALI = 'Bengali';
    const BHAIKSUKI = 'Bhaiksuki';
    const BLOCK_ELEMENTS = 'Block_Elements';
    const BOPOMOFO = 'Bopomofo';
    const BOPOMOFO_EXTENDED = 'Bopomofo_Ext';
    const BOX_DRAWING = 'Box_Drawing';
    const BRAHMI = 'Brahmi';
    const BRAILLE_PATTERNS = 'Braille';
    const BUGINESE = 'Buginese';
    const BUHID = 'Buhid';
    const BYZANTINE_MUSICAL_SYMBOLS = 'Byzantine_Music';
    const CARIAN = 'Carian';
    const CAUCASIAN_ALBANIAN = 'Caucasian_Albanian';
    const CHAKMA = 'Chakma';
    const CHAM = 'Cham';
    const CHEROKEE = 'Cherokee';
    const CHEROKEE_SUPPLEMENT = 'Cherokee_Sup';
    const CHESS_SYMBOLS = 'Chess_Symbols';
    const CHORASMIAN = 'Chorasmian';
    const CJK_UNIFIED_IDEOGRAPHS = 'CJK';
    const CJK_COMPATIBILITY = 'CJK_Compat';
    const CJK_COMPATIBILITY_FORMS = 'CJK_Compat_Forms';
    const CJK_COMPATIBILITY_IDEOGRAPHS = 'CJK_Compat_Ideographs';
    const CJK_COMPATIBILITY_IDEOGRAPHS_SUPPLEMENT = 'CJK_Compat_Ideographs_Sup';
    const CJK_UNIFIED_IDEOGRAPHS_EXTENSION_A = 'CJK_Ext_A';
    const CJK_UNIFIED_IDEOGRAPHS_EXTENSION_B = 'CJK_Ext_B';
    const CJK_UNIFIED_IDEOGRAPHS_EXTENSION_C = 'CJK_Ext_C';
    const CJK_UNIFIED_IDEOGRAPHS_EXTENSION_D = 'CJK_Ext_D';
    const CJK_UNIFIED_IDEOGRAPHS_EXTENSION_E = 'CJK_Ext_E';
    const CJK_UNIFIED_IDEOGRAPHS_EXTENSION_F = 'CJK_Ext_F';
    const CJK_UNIFIED_IDEOGRAPHS_EXTENSION_G = 'CJK_Ext_G';
    const CJK_RADICALS_SUPPLEMENT = 'CJK_Radicals_Sup';
    const CJK_STROKES = 'CJK_Strokes';
    const CJK_SYMBOLS_AND_PUNCTUATION = 'CJK_Symbols';
    const HANGUL_COMPATIBILITY_JAMO = 'Compat_Jamo';
    const CONTROL_PICTURES = 'Control_Pictures';
    const COPTIC = 'Coptic';
    const COPTIC_EPACT_NUMBERS = 'Coptic_Epact_Numbers';
    const COUNTING_ROD_NUMERALS = 'Counting_Rod';
    const CUNEIFORM = 'Cuneiform';
    const CUNEIFORM_NUMBERS_AND_PUNCTUATION = 'Cuneiform_Numbers';
    const CURRENCY_SYMBOLS = 'Currency_Symbols';
    const CYPRIOT_SYLLABARY = 'Cypriot_Syllabary';
    const CYRILLIC = 'Cyrillic';
    const CYRILLIC_EXTENDED_A = 'Cyrillic_Ext_A';
    const CYRILLIC_EXTENDED_B = 'Cyrillic_Ext_B';
    const CYRILLIC_EXTENDED_C = 'Cyrillic_Ext_C';
    const CYRILLIC_SUPPLEMENT = 'Cyrillic_Sup';
    const DESERET = 'Deseret';
    const DEVANAGARI = 'Devanagari';
    const DEVANAGARI_EXTENDED = 'Devanagari_Ext';
    const COMBINING_DIACRITICAL_MARKS = 'Diacriticals';
    const COMBINING_DIACRITICAL_MARKS_EXTENDED = 'Diacriticals_Ext';
    const COMBINING_DIACRITICAL_MARKS_FOR_SYMBOLS = 'Diacriticals_For_Symbols';
    const COMBINING_DIACRITICAL_MARKS_SUPPLEMENT = 'Diacriticals_Sup';
    const DINGBATS = 'Dingbats';
    const DIVES_AKURU = 'Dives_Akuru';
    const DOGRA = 'Dogra';
    const DOMINO_TILES = 'Domino';
    const DUPLOYAN = 'Duployan';
    const EARLY_DYNASTIC_CUNEIFORM = 'Early_Dynastic_Cuneiform';
    const EGYPTIAN_HIEROGLYPH_FORMAT_CONTROLS = 'Egyptian_Hieroglyph_Format_Controls';
    const EGYPTIAN_HIEROGLYPHS = 'Egyptian_Hieroglyphs';
    const ELBASAN = 'Elbasan';
    const ELYMAIC = 'Elymaic';
    const EMOTICONS = 'Emoticons';
    const ENCLOSED_ALPHANUMERICS = 'Enclosed_Alphanum';
    const ENCLOSED_ALPHANUMERIC_SUPPLEMENT = 'Enclosed_Alphanum_Sup';
    const ENCLOSED_CJK_LETTERS_AND_MONTHS = 'Enclosed_CJK';
    const ENCLOSED_IDEOGRAPHIC_SUPPLEMENT = 'Enclosed_Ideographic_Sup';
    const ETHIOPIC = 'Ethiopic';
    const ETHIOPIC_EXTENDED = 'Ethiopic_Ext';
    const ETHIOPIC_EXTENDED_A = 'Ethiopic_Ext_A';
    const ETHIOPIC_SUPPLEMENT = 'Ethiopic_Sup';
    const GEOMETRIC_SHAPES = 'Geometric_Shapes';
    const GEOMETRIC_SHAPES_EXTENDED = 'Geometric_Shapes_Ext';
    const GEORGIAN = 'Georgian';
    const GEORGIAN_EXTENDED = 'Georgian_Ext';
    const GEORGIAN_SUPPLEMENT = 'Georgian_Sup';
    const GLAGOLITIC = 'Glagolitic';
    const GLAGOLITIC_SUPPLEMENT = 'Glagolitic_Sup';
    const GOTHIC = 'Gothic';
    const GRANTHA = 'Grantha';
    const GREEK_AND_COPTIC = 'Greek';
    const GREEK_EXTENDED = 'Greek_Ext';
    const GUNJALA_GONDI = 'Gunjala_Gondi';
    const GUJARATI = 'Gujarati';
    const GURMUKHI = 'Gurmukhi';
    const HALFWIDTH_AND_FULLWIDTH_FORMS = 'Half_And_Full_Forms';
    const COMBINING_HALF_MARKS = 'Half_Marks';
    const HANGUL_SYLLABLES = 'Hangul';
    const HANIFI_ROHINGYA = 'Hanifi_Rohingya';
    const HANUNOO = 'Hanunoo';
    const HATRAN = 'Hatran';
    const HEBREW = 'Hebrew';
    const HIGH_PRIVATE_USE_SURROGATES = 'High_PU_Surrogates';
    const HIGH_SURROGATES = 'High_Surrogates';
    const HIRAGANA = 'Hiragana';
    const IDEOGRAPHIC_DESCRIPTION_CHARACTERS = 'IDC';
    const IDEOGRAPHIC_SYMBOLS_AND_PUNCTUATION = 'Ideographic_Symbols';
    const IMPERIAL_ARAMAIC = 'Imperial_Aramaic';
    const COMMON_INDIC_NUMBER_FORMS = 'Indic_Number_Forms';
    const INDIC_SIYAQ_NUMBERS = 'Indic_Siyaq_Numbers';
    const INSCRIPTIONAL_PAHLAVI = 'Inscriptional_Pahlavi';
    const INSCRIPTIONAL_PARTHIAN = 'Inscriptional_Parthian';
    const IPA_EXTENSIONS = 'IPA_Ext';
    const HANGUL_JAMO = 'Jamo';
    const HANGUL_JAMO_EXTENDED_A = 'Jamo_Ext_A';
    const HANGUL_JAMO_EXTENDED_B = 'Jamo_Ext_B';
    const JAVANESE = 'Javanese';
    const KAITHI = 'Kaithi';
    const KANA_SUPPLEMENT = 'Kana_Sup';
    const KANA_EXTENDED_A = 'Kana_Ext_A';
    const KANBUN = 'Kanbun';
    const KANGXI_RADICALS = 'Kangxi';
    const KANNADA = 'Kannada';
    const KATAKANA = 'Katakana';
    const KATAKANA_PHONETIC_EXTENSIONS = 'Katakana_Ext';
    const KAYAH_LI = 'Kayah_Li';
    const KHAROSHTHI = 'Kharoshthi';
    const KHITAN_SMALL_SCRIPT = 'Khitan_Small_Script';
    const KHMER = 'Khmer';
    const KHMER_SYMBOLS = 'Khmer_Symbols';
    const KHOJKI = 'Khojki';
    const KHUDAWADI = 'Khudawadi';
    const LAO = 'Lao';
    const LATIN_1_SUPPLEMENT = 'Latin_1_Sup';
    const LATIN_EXTENDED_A = 'Latin_Ext_A';
    const LATIN_EXTENDED_ADDITIONAL = 'Latin_Ext_Additional';
    const LATIN_EXTENDED_B = 'Latin_Ext_B';
    const LATIN_EXTENDED_C = 'Latin_Ext_C';
    const LATIN_EXTENDED_D = 'Latin_Ext_D';
    const LATIN_EXTENDED_E = 'Latin_Ext_E';
    const LEPCHA = 'Lepcha';
    const LETTERLIKE_SYMBOLS = 'Letterlike_Symbols';
    const LIMBU = 'Limbu';
    const LINEAR_A = 'Linear_A';
    const LINEAR_B_IDEOGRAMS = 'Linear_B_Ideograms';
    const LINEAR_B_SYLLABARY = 'Linear_B_Syllabary';
    const LISU = 'Lisu';
    const LISU_SUPPLEMENT = 'Lisu_Sup';
    const LOW_SURROGATES = 'Low_Surrogates';
    const LYCIAN = 'Lycian';
    const LYDIAN = 'Lydian';
    const MAHAJANI = 'Mahajani';
    const MAHJONG_TILES = 'Mahjong';
    const MAKASAR = 'Makasar';
    const MALAYALAM = 'Malayalam';
    const MANDAIC = 'Mandaic';
    const MANICHAEAN = 'Manichaean';
    const MARCHEN = 'Marchen';
    const MASARAM_GONDI = 'Masaram_Gondi';
    const MATHEMATICAL_ALPHANUMERIC_SYMBOLS = 'Math_Alphanum';
    const MATHEMATICAL_OPERATORS = 'Math_Operators';
    const MAYAN_NUMERALS = 'Mayan_Numerals';
    const MEDEFAIDRIN = 'Medefaidrin';
    const MEETEI_MAYEK = 'Meetei_Mayek';
    const MEETEI_MAYEK_EXTENSIONS = 'Meetei_Mayek_Ext';
    const MENDE_KIKAKUI = 'Mende_Kikakui';
    const MEROITIC_CURSIVE = 'Meroitic_Cursive';
    const MEROITIC_HIEROGLYPHS = 'Meroitic_Hieroglyphs';
    const MIAO = 'Miao';
    const MISCELLANEOUS_SYMBOLS_AND_ARROWS = 'Misc_Arrows';
    const MISCELLANEOUS_MATHEMATICAL_SYMBOLS_A = 'Misc_Math_Symbols_A';
    const MISCELLANEOUS_MATHEMATICAL_SYMBOLS_B = 'Misc_Math_Symbols_B';
    const MISCELLANEOUS_SYMBOLS_AND_PICTOGRAPHS = 'Misc_Pictographs';
    const MISCELLANEOUS_SYMBOLS = 'Misc_Symbols';
    const MISCELLANEOUS_TECHNICAL = 'Misc_Technical';
    const MODI = 'Modi';
    const SPACING_MODIFIER_LETTERS = 'Modifier_Letters';
    const MODIFIER_TONE_LETTERS = 'Modifier_Tone_Letters';
    const MONGOLIAN = 'Mongolian';
    const MONGOLIAN_SUPPLEMENT = 'Mongolian_Sup';
    const MRO = 'Mro';
    const MULTANI = 'Multani';
    const MUSICAL_SYMBOLS = 'Music';
    const MYANMAR = 'Myanmar';
    const MYANMAR_EXTENDED_A = 'Myanmar_Ext_A';
    const MYANMAR_EXTENDED_B = 'Myanmar_Ext_B';
    const NABATAEAN = 'Nabataean';
    const NANDINAGARI = 'Nandinagari';
    const NO_BLOCK = 'NB';
    const NEW_TAI_LUE = 'New_Tai_Lue';
    const NEWA = 'Newa';
    const NKO = 'NKo';
    const NUMBER_FORMS = 'Number_Forms';
    const NUSHU = 'Nushu';
    const NYIAKENG_PUACHUE_HMONG = 'Nyiakeng_Puachue_Hmong';
    const OPTICAL_CHARACTER_RECOGNITION = 'OCR';
    const OGHAM = 'Ogham';
    const OL_CHIKI = 'Ol_Chiki';
    const OLD_HUNGARIAN = 'Old_Hungarian';
    const OLD_ITALIC = 'Old_Italic';
    const OLD_NORTH_ARABIAN = 'Old_North_Arabian';
    const OLD_PERMIC = 'Old_Permic';
    const OLD_PERSIAN = 'Old_Persian';
    const OLD_SOGDIAN = 'Old_Sogdian';
    const OLD_SOUTH_ARABIAN = 'Old_South_Arabian';
    const OLD_TURKIC = 'Old_Turkic';
    const ORIYA = 'Oriya';
    const ORNAMENTAL_DINGBATS = 'Ornamental_Dingbats';
    const OSAGE = 'Osage';
    const OSMANYA = 'Osmanya';
    const OTTOMAN_SIYAQ_NUMBERS = 'Ottoman_Siyaq_Numbers';
    const PAHAWH_HMONG = 'Pahawh_Hmong';
    const PALMYRENE = 'Palmyrene';
    const PAU_CIN_HAU = 'Pau_Cin_Hau';
    const PHAGS_PA = 'Phags_Pa';
    const PHAISTOS_DISC = 'Phaistos';
    const PHOENICIAN = 'Phoenician';
    const PHONETIC_EXTENSIONS = 'Phonetic_Ext';
    const PHONETIC_EXTENSIONS_SUPPLEMENT = 'Phonetic_Ext_Sup';
    const PLAYING_CARDS = 'Playing_Cards';
    const PSALTER_PAHLAVI = 'Psalter_Pahlavi';
    const PRIVATE_USE_AREA = 'PUA';
    const GENERAL_PUNCTUATION = 'Punctuation';
    const REJANG = 'Rejang';
    const RUMI_NUMERAL_SYMBOLS = 'Rumi';
    const RUNIC = 'Runic';
    const SAMARITAN = 'Samaritan';
    const SAURASHTRA = 'Saurashtra';
    const SHARADA = 'Sharada';
    const SHAVIAN = 'Shavian';
    const SHORTHAND_FORMAT_CONTROLS = 'Shorthand_Format_Controls';
    const SIDDHAM = 'Siddham';
    const SINHALA = 'Sinhala';
    const SINHALA_ARCHAIC_NUMBERS = 'Sinhala_Archaic_Numbers';
    const SMALL_FORM_VARIANTS = 'Small_Forms';
    const SMALL_KANA_EXTENSION = 'Small_Kana_Ext';
    const SOGDIAN = 'Sogdian';
    const SORA_SOMPENG = 'Sora_Sompeng';
    const SOYOMBO = 'Soyombo';
    const SPECIALS = 'Specials';
    const SUNDANESE = 'Sundanese';
    const SUNDANESE_SUPPLEMENT = 'Sundanese_Sup';
    const SUPPLEMENTAL_ARROWS_A = 'Sup_Arrows_A';
    const SUPPLEMENTAL_ARROWS_B = 'Sup_Arrows_B';
    const SUPPLEMENTAL_ARROWS_C = 'Sup_Arrows_C';
    const SUPPLEMENTAL_MATHEMATICAL_OPERATORS = 'Sup_Math_Operators';
    const SUPPLEMENTARY_PRIVATE_USE_AREA_A = 'Sup_PUA_A';
    const SUPPLEMENTARY_PRIVATE_USE_AREA_B = 'Sup_PUA_B';
    const SUPPLEMENTAL_PUNCTUATION = 'Sup_Punctuation';
    const SUPPLEMENTAL_SYMBOLS_AND_PICTOGRAPHS = 'Sup_Symbols_And_Pictographs';
    const SUPERSCRIPTS_AND_SUBSCRIPTS = 'Super_And_Sub';
    const SUTTON_SIGNWRITING = 'Sutton_SignWriting';
    const SYLOTI_NAGRI = 'Syloti_Nagri';
    const SYMBOLS_AND_PICTOGRAPHS_EXTENDED_A = 'Symbols_And_Pictographs_Ext_A';
    const SYMBOLS_FOR_LEGACY_COMPUTING = 'Symbols_For_Legacy_Computing';
    const SYRIAC = 'Syriac';
    const SYRIAC_SUPPLEMENT = 'Syriac_Sup';
    const TAGALOG = 'Tagalog';
    const TAGBANWA = 'Tagbanwa';
    const TAGS = 'Tags';
    const TAI_LE = 'Tai_Le';
    const TAI_THAM = 'Tai_Tham';
    const TAI_VIET = 'Tai_Viet';
    const TAI_XUAN_JING_SYMBOLS = 'Tai_Xuan_Jing';
    const TAKRI = 'Takri';
    const TAMIL = 'Tamil';
    const TAMIL_SUPPLEMENT = 'Tamil_Sup';
    const TANGUT = 'Tangut';
    const TANGUT_COMPONENTS = 'Tangut_Components';
    const TANGUT_SUPPLEMENT = 'Tangut_Sup';
    const TELUGU = 'Telugu';
    const THAANA = 'Thaana';
    const THAI = 'Thai';
    const TIBETAN = 'Tibetan';
    const TIFINAGH = 'Tifinagh';
    const TIRHUTA = 'Tirhuta';
    const TRANSPORT_AND_MAP_SYMBOLS = 'Transport_And_Map';
    const UNIFIED_CANADIAN_ABORIGINAL_SYLLABICS = 'UCAS';
    const UNIFIED_CANADIAN_ABORIGINAL_SYLLABICS_EXTENDED = 'UCAS_Ext';
    const UGARITIC = 'Ugaritic';
    const VAI = 'Vai';
    const VEDIC_EXTENSIONS = 'Vedic_Ext';
    const VERTICAL_FORMS = 'Vertical_Forms';
    const VARIATION_SELECTORS = 'VS';
    const VARIATION_SELECTORS_SUPPLEMENT = 'VS_Sup';
    const WANCHO = 'Wancho';
    const WARANG_CITI = 'Warang_Citi';
    const YEZIDI = 'Yezidi';
    const YI_RADICALS = 'Yi_Radicals';
    const YI_SYLLABLES = 'Yi_Syllables';
    const YIJING_HEXAGRAM_SYMBOLS = 'Yijing';
    const ZANABAZAR_SQUARE = 'Zanabazar_Square';
}