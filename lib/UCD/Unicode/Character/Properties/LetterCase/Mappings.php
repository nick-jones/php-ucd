<?php

namespace UCD\Unicode\Character\Properties\LetterCase;

use UCD\Unicode\Codepoint;

class Mappings
{
    /**
     * @var Mapping
     */
    private $lowercase;

    /**
     * @var Mapping
     */
    private $uppercase;

    /**
     * @var Mapping
     */
    private $titlecase;

    /**
     * @var Mapping
     */
    private $folding;

    /**
     * @param Mapping $lowercase
     * @param Mapping $uppercase
     * @param Mapping $titlecase
     * @param Mapping $folding
     */
    public function __construct(
        Mapping $lowercase,
        Mapping $uppercase,
        Mapping $titlecase,
        Mapping $folding
    ) {
        $this->lowercase = $lowercase;
        $this->uppercase = $uppercase;
        $this->titlecase = $titlecase;
        $this->folding = $folding;
    }

    /**
     * @return Mapping
     */
    public function getLowercase()
    {
        return $this->lowercase;
    }

    /**
     * @return Mapping
     */
    public function getUppercase()
    {
        return $this->uppercase;
    }

    /**
     * @return Mapping
     */
    public function getTitlecase()
    {
        return $this->titlecase;
    }

    /**
     * @return Mapping
     */
    public function getFolding()
    {
        return $this->folding;
    }
}