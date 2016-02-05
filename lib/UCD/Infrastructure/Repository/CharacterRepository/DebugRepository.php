<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use Psr\Log\LoggerInterface;

use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Character\Repository;
use UCD\Unicode\CodepointAssigned;

class DebugRepository implements Repository
{
    /**
     * @var Repository
     */
    protected $delegate;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Repository $delegate
     * @param LoggerInterface $logger
     */
    public function __construct(Repository $delegate, LoggerInterface $logger)
    {
        $this->delegate = $delegate;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function getByCodepoint(Codepoint $codepoint)
    {
        $message = $this->composeMessage(__FUNCTION__, [$codepoint]);
        $this->log($message);

        return $this->delegate->getByCodepoint($codepoint);
    }

    /**
     * {@inheritDoc}
     */
    public function getByCodepoints(Codepoint\Collection $codepoints)
    {
        $message = $this->composeMessage(__FUNCTION__, [$codepoints]);
        $this->log($message);

        return $this->delegate->getByCodepoints($codepoints);
    }

    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        $message = $this->composeMessage(__FUNCTION__);
        $this->log($message);

        return $this->delegate->getAll();
    }

    /**
     * {@inheritDoc}
     */
    public function getCodepointsByBlock(Block $block)
    {
        $message = $this->composeMessage(__FUNCTION__);
        $this->log($message);

        return $this->delegate->getCodepointsByBlock($block);
    }

    /**
     * {@inheritDoc}
     */
    public function getCodepointsByCategory(GeneralCategory $category)
    {
        $message = $this->composeMessage(__FUNCTION__);
        $this->log($message);

        return $this->delegate->getCodepointsByCategory($category);
    }

    /**
     * {@inheritDoc}
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