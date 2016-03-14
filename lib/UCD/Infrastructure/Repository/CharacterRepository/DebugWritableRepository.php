<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use Psr\Log\LoggerInterface;

use UCD\Unicode\Character\Collection;
use UCD\Unicode\Character\WritableRepository;
use UCD\Unicode\CodepointAssigned;
use UCD\Unicode\Character\Repository;

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
            $this->logMethodCall($function, [(string)$c->getCodepoint()]);
        });

        $this->delegate->addMany($characters);
        $this->notify();
    }
}