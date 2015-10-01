<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use Psr\Log\LoggerInterface;
use UCD\Entity\Character;
use UCD\Entity\Character\WritableRepository;

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
     * @param Character[] $characters
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