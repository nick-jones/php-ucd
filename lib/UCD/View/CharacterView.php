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
        $codepoint = $this->character->getCodepointValue();

        return iconv('UTF-32BE', 'UTF-8', pack('N', $codepoint));
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