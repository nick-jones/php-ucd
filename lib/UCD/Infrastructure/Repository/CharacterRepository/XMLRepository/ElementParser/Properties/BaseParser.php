<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\Properties;

use UCD\Unicode\Codepoint;
use UCD\Exception\UnexpectedValueException;

use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\ElementParser\CodepointAwareParser;

abstract class BaseParser implements CodepointAwareParser
{
    const ATTRIBUTE_VALUE_YES = 'Y';

    /**
     * @var \DOMElement
     */
    protected $element;

    /**
     * @var Codepoint
     */
    protected $codepoint;

    /**
     * @return mixed
     */
    abstract protected function parse();

    /**
     * @param \DOMElement $element
     * @param Codepoint $codepoint
     * @return mixed
     */
    public function parseElement(\DOMElement $element, Codepoint $codepoint)
    {
        $this->element = $element;
        $this->codepoint = $codepoint;

        return $this->parse();
    }

    /**
     * @param string $name
     * @return string|null
     */
    protected function getOptionalAttribute($name)
    {
        $attribute = $this->element->getAttribute($name);

        if ($attribute === '') {
            return null;
        }

        return $attribute;
    }

    /**
     * @param string $name
     * @return string
     * @throws UnexpectedValueException
     */
    protected function getAttribute($name)
    {
        $attribute = $this->element->getAttribute($name);

        if ($attribute === '') {
            throw new UnexpectedValueException(sprintf('Missing attribute "%s"', $name));
        }

        return $attribute;
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function getBoolAttribute($name)
    {
        return $this->getAttribute($name) === self::ATTRIBUTE_VALUE_YES;
    }

    /**
     * @param string $value
     * @param Codepoint $codepoint
     * @return string
     */
    protected function parsePlaceholders($value, Codepoint $codepoint)
    {
        if ($value === null) {
            return null;
        }

        $hexCodepoint = sprintf('%X', $codepoint->getValue());

        return str_replace('#', $hexCodepoint, $value);
    }

    /**
     * @param string $list
     * @return Codepoint[]
     */
    protected function parseCodepointList($list)
    {
        $mapper = function ($codepointValue) {
            return Codepoint::fromHex($codepointValue);
        };

        return array_map($mapper, explode(' ', $list));
    }
}