<?php

namespace UCD\Infrastructure\Repository\CharacterRepository;

use Psr\Log\LoggerInterface;

use UCD\Unicode\Character\Properties\General\Block;
use UCD\Unicode\Character\Properties\General\GeneralCategory;
use UCD\Unicode\Character\Properties\General\Script;
use UCD\Unicode\Codepoint;
use UCD\Unicode\Character\Repository;

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
        $this->logMethodCall(__FUNCTION__, [$codepoint]);

        return $this->delegate->getByCodepoint($codepoint);
    }

    /**
     * {@inheritDoc}
     */
    public function getByCodepoints(Codepoint\Collection $codepoints)
    {
        $this->logMethodCall(__FUNCTION__, [$codepoints]);

        return $this->delegate->getByCodepoints($codepoints);
    }

    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        $this->logMethodCall(__FUNCTION__);

        return $this->delegate->getAll();
    }

    /**
     * {@inheritDoc}
     */
    public function getCodepointsByBlock(Block $block)
    {
        $this->logMethodCall(__FUNCTION__);

        return $this->delegate->getCodepointsByBlock($block);
    }

    /**
     * {@inheritDoc}
     */
    public function getCodepointsByCategory(GeneralCategory $category)
    {
        $this->logMethodCall(__FUNCTION__);

        return $this->delegate->getCodepointsByCategory($category);
    }

    /**
     * {@inheritDoc}
     */
    public function getCodepointsByScript(Script $script)
    {
        $this->logMethodCall(__FUNCTION__);

        return $this->delegate->getCodepointsByScript($script);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        $this->logMethodCall(__FUNCTION__);

        return $this->delegate->count();
    }

    /**
     * @param string $method
     * @param array $details
     */
    protected function logMethodCall($method, array $details = [])
    {
        $message = $this->composeMessageFromMethodDetails($method, $details);
        $this->log($message);
    }

    /**
     * @param string $method
     * @param array $details
     * @return string
     */
    protected function composeMessageFromMethodDetails($method, array $details = [])
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