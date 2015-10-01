<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;

final class StreamingElementReader implements ElementReader
{
    const ELEMENT_CHARACTER = 'char';
    const ELEMENT_NON_CHARACTER = 'noncharacter';
    const ELEMENT_SURROGATE = 'surrogate';

    /**
     * @var array
     */
    private static $elements = [
        self::ELEMENT_CHARACTER => true,
        self::ELEMENT_NON_CHARACTER => true,
        self::ELEMENT_SURROGATE => true
    ];

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

        while ($this->cursorHasElement() !== true) {
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
    private function cursorHasElement()
    {
        return $this->xmlReader->nodeType === XMLReader::ELEMENT
            && array_key_exists($this->xmlReader->name, self::$elements);
    }

    /**
     * @return \DOMElement|null
     */
    private function readNext()
    {
        if (!$this->cursorHasElement()) {
            return null;
        }

        $element = $this->xmlReader->expand();
        $this->moveToNextElement();

        return $element;
    }

    private function moveToNextElement()
    {
        while ($this->xmlReader->next() !== false) {
            if ($this->cursorHasElement()) {
                break;
            }
        }
    }
}