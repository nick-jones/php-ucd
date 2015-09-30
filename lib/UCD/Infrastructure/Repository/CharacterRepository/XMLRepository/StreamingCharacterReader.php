<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;

final class StreamingCharacterReader implements ElementReader
{
    const ELEMENT_CHAR = 'char';

    /**
     * @var XMLReader
     */
    private $xmlReader;

    /**
     * @param XMLReader $xmlReader
     */
    public function __construct(XMLReader $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    /**
     * @return \DOMElement[]
     */
    public function read()
    {
        $this->preRead();

        try {
            while ($element = $this->readNext()) {
                yield $element;
            }
        } finally {
            $this->postRead();
        }
    }

    private function preRead()
    {
        $this->xmlReader->reopen();

        while ($this->cursorHasCharacter() !== true) {
            $this->xmlReader->read();
        }
    }

    private function postRead()
    {
        $this->xmlReader->close();
    }

    /**
     * @return bool
     */
    private function cursorHasCharacter()
    {
        return $this->xmlReader->name === self::ELEMENT_CHAR;
    }

    /**
     * @return \DOMElement|null
     */
    private function readNext()
    {
        if ($this->cursorHasCharacter() !== true) {
            return null;
        }

        $element = $this->xmlReader->expand();
        $this->xmlReader->next(self::ELEMENT_CHAR);

        return $element;
    }
}