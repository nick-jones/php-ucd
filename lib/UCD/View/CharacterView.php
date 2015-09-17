<?php

namespace UCD\View;

use SebastianBergmann\Exporter\Exporter;
use UCD\Entity\Character;

class CharacterView
{
    /**
     * @var Character
     */
    private $character;

    /**
     * @param Character $character
     */
    public function __construct(Character $character)
    {
        $this->character = $character;
    }

    /**
     * @return string
     */
    public function asUTF8()
    {
        $bytes = [];
        $codepoint = $this->character->getCodepointValue();

        if ($codepoint < 0x80) {
            $bytes[0] = chr($codepoint & 0x7F);
        } elseif ($codepoint < 0x800) {
            $bytes[0] = chr($codepoint >> 6 & 0x1F | 0xC0);
            $bytes[1] = chr($codepoint & 0x3F | 0x80);
        } elseif ($codepoint < 0x10000) {
            $bytes[0] = chr($codepoint >> 12 & 0x0F | 0xE0);
            $bytes[1] = chr($codepoint >> 6 & 0x3F | 0x80);
            $bytes[2] = chr($codepoint & 0x3F | 0x80);
        } else {
            $bytes[0] = chr($codepoint >> 18 & 0x07 | 0xF0);
            $bytes[1] = chr($codepoint >> 12 & 0x3F | 0x80);
            $bytes[2] = chr($codepoint >> 6 & 0x3F | 0x80);
            $bytes[3] = chr($codepoint & 0x3F | 0x80);
        }

        return implode('', $bytes);
    }

    /**
     * @return string
     */
    public function asExport()
    {
        $exporter = new Exporter();

        return $exporter->export($this->character);
    }
}