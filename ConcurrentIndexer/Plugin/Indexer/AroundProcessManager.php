<?php
namespace Betagento\ConcurrentIndexer\Plugin\Indexer;

use Spatie\Fork\Fork;
use Betagento\ConcurrentIndexer\Model\Config;

class AroundProcessManager
{
    
    public function __construct(
        protected Config $config
    )
    {}

    /**
     * Summary of aroundExecute
     * @param \Magento\Indexer\Model\ProcessManager $subject
     * @param callable $procceed
     * @param array<callable> $userFunctions
     * @return void
     */
    public function aroundExecute($subject, $procceed, $userFunctions): void {
        
        if ($this->config->isEnabled() && $this->config->getThreadCount() > 1 && $this->config->isCanBeParalleled() && !$this->config->isSetupMode() && PHP_SAPI == 'cli') {

            Fork::new()->concurrent($this->config->getThreadCount())->run(...$userFunctions);
            return;
        }
        $procceed($userFunctions);
    }

    
}