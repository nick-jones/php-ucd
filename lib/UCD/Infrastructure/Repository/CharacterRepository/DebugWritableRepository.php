<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use Psr\Log\LoggerInterface;

use UCD\Entity\Character\WritableRepository;
use UCD\Entity\CodepointAssigned;

class DebugWritableRepository extends DebugReadonlyRepository implements WritableRepository
{
    /**
     * @var WritableRepository
     */
    protected $delegate;

    /**
     * @param WritableRepository $delegate
     * @param LoggerInterface $logger
     */
    public function __construct(WritableRepository $delegate, LoggerInterface $logger)
    {
        parent::__construct($delegate, $logger);
    }

    /**
     * @param CodepointAssigned[] $characters
     */
    public function addMany($characters)
    {
        foreach ($characters as $character) {
            $message = $this->composeMessage(__FUNCTION__, [(string)$character->getCodepoint()]);
            $this->log($message);
        }

        $this->delegate->addMany($characters);
    }
}