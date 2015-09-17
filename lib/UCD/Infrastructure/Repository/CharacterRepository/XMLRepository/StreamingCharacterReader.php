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
     * @return \Traversable|\DOMElement[]
     */
    public function read()
    {
        $this->preRead();

        while ($element = $this->readNext()) {
            yield $element;
        }
    }

    private function preRead()
    {
        $this->xmlReader->rewind();

        while ($this->cursorHasCharacter() !== true) {
            $this->xmlReader->read();
        }
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