<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use Psr\Log\LoggerInterface;

use UCD\Entity\Character\Collection;
use UCD\Entity\Character\WritableRepository;
use UCD\Entity\CodepointAssigned;
use UCD\Entity\Character\Repository;

class DebugWritableRepository extends DebugRepository implements WritableRepository
{
    use Repository\Capability\Notify;

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
     * {@inheritDoc}
     */
    public function addMany(Collection $characters)
    {
        $function = __FUNCTION__;

        $characters->traverseWith(function (CodepointAssigned $c) use ($function) {
            $message = $this->composeMessage($function, [(string)$c->getCodepoint()]);
            $this->log($message);
        });

        $this->delegate->addMany($characters);
        $this->notify();
    }
}