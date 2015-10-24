<?php

namespace UCD\Traverser;

use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;

class CodepointAccumulator extends Traverser
{
    /**
     * @var Codepoint[]
     */
    private $codepoints = [];

    /**
     * @param CodepointAssigned $entity
     */
    protected function consume(CodepointAssigned $entity)
    {
        $this->addCodepoint($entity->getCodepoint());
    }

    /**
     * @param Codepoint $codepoint
     */
    private function addCodepoint(Codepoint $codepoint)
    {
        $this->codepoints[] = $codepoint;
    }

    /**
     * @return Codepoint[]
     */
    public function getCodepoints()
    {
        return $this->codepoints;
    }
}