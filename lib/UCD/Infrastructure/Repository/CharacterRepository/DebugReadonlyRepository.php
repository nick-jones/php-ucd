<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use Psr\Log\LoggerInterface;

use UCD\Entity\Codepoint;
use UCD\Entity\Character\ReadOnlyRepository;
use UCD\Entity\CodepointAssigned;

class DebugReadonlyRepository implements ReadOnlyRepository
{
    /**
     * @var ReadOnlyRepository
     */
    protected $delegate;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ReadOnlyRepository $delegate
     * @param LoggerInterface $logger
     */
    public function __construct(ReadOnlyRepository $delegate, LoggerInterface $logger)
    {
        $this->delegate = $delegate;
        $this->logger = $logger;
    }

    /**
     * @param Codepoint $codepoint
     * @return CodepointAssigned
     */
    public function getByCodepoint(Codepoint $codepoint)
    {
        $message = $this->composeMessage(__FUNCTION__, [$codepoint]);
        $this->log($message);

        return $this->delegate->getByCodepoint($codepoint);
    }

    /**
     * @return CodepointAssigned[]
     */
    public function getAll()
    {
        $message = $this->composeMessage(__FUNCTION__);
        $this->log($message);

        return $this->delegate->getAll();
    }

    /**
     * @return int
     */
    public function count()
    {
        $message = $this->composeMessage(__FUNCTION__);
        $this->log($message);

        return $this->delegate->count();
    }

    /**
     * @param string $method
     * @param array $details
     * @return string
     */
    protected function composeMessage($method, array $details = [])
    {
        return sprintf('Repository::%s/%s', $method, json_encode($details));
    }

    /**
     * @param string $message
     */
    protected function log($message)
    {
        $this->logger->info($message);
    }
}