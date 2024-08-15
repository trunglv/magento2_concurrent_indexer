<?php
namespace Betagento\ConcurrentIndexer\Plugin\Indexer\Flat;

use Spatie\Fork\Fork;
use Betagento\ConcurrentIndexer\Model\Indexer\Flat\Action\Full as CustomizedFullAction;
use Betagento\ConcurrentIndexer\Model\Config;

class AroundFullPlugin 
{
    
    public function __construct(
        protected CustomizedFullAction $customizedFullAction,
        protected Config $config
    )
    {}
    /**
     * Summary of aroundExecute
     * @param \Magento\Catalog\Model\Indexer\Product\Flat\Action\Full $subject
     * @param callable $procceed
     * @param array<int>|null $ids
     * @return \Magento\Catalog\Model\Indexer\Product\Flat\Action\Full
     */
    public function aroundExecute($subject, $procceed, $ids = null)
    {
        if ($this->config->isEnabled() && $this->config->getThreadCount() > 1 && $this->config->isCanBeParalleled() && !$this->config->isSetupMode() && PHP_SAPI == 'cli') {
            $processes = $this->customizedFullAction->execute($ids)->getProcesses();
            Fork::new()->concurrent($this->config->getThreadCount())->run(...$processes);
            return $subject;
        }
        return $procceed($ids);
        
    }
}
