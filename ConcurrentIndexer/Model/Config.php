<?php
namespace Betagento\ConcurrentIndexer\Model;

class Config
{
    const IS_ENABLED = 'BETA_CONCURRENT_INDEXER_THREADS_ENABLED';

    public function __construct(
        protected \Magento\Framework\Registry $registry,
        protected int $threadsCount = 0,
        protected int $isEnabled = 0
    )
    {}
    
    /**
     * Summary of isSetupMode
     * @return bool
     */
    public function isSetupMode(): bool
    {
        return $this->registry->registry('setup-mode-enabled') ? true : false;
    }

    /**
     * Summary of isCanBeParalleled
     * @return bool
     */
    public function isCanBeParalleled(): bool
    {
        return function_exists('pcntl_fork');
    }

    /**
     * Summary of getThreadCount
     * @return int
     */
    public function getThreadCount(): int
    {
        return $this->threadsCount;
    }

    /**
     * Summary of isEnabled
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (int)$this->isEnabled === 1;
    }

}