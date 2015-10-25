<?php

namespace UCD\Consumer;

use UCD\Entity\Codepoint;
use UCD\Entity\CodepointAssigned;

class CodepointAccumulatingConsumer implements Consumer
{
    /**
     * @var Codepoint[]
     */
    private $codepoints = [];

    /**
     * @param CodepointAssigned $entity
     */
    public function consume(CodepointAssigned $entity)
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