<?php

namespace UCD\Unicode\Character;

use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Character\Properties\General\Script;
use UCD\Unicode\Character\Repository\CharacterNotFoundException;
use UCD\Unicode\Codepoint;
use UCD\Unicode\CodepointAssigned;

interface Repository extends \Countable
{
    /**
     * @param Codepoint $codepoint
     * @return CodepointAssigned
     * @throws CharacterNotFoundException
     */
    public function getByCodepoint(Codepoint $codepoint);

    /**
     * @param Codepoint\Collection|Codepoint[] $codepoints
     * @return Collection|CodepointAssigned[]
     */
    public function getByCodepoints(Codepoint\Collection $codepoints);

    /**
     * @return Collection|CodepointAssigned[]
     */
    public function getAll();

    /**
     * @param Block $block
     * @return Codepoint\Range\Collection
     */
    public function getCodepointsByBlock(Block $block);

    /**
     * @param GeneralCategory $category
     * @return Codepoint\Range\Collection
     */
    public function getCodepointsByCategory(GeneralCategory $category);

    /**
     * @param Script $script
     * @return Codepoint\Range\Collection
     */
    public function getCodepointsByScript(Script $script);
}