<?php
namespace Betagento\ConcurrentIndexer\Plugin\Indexer\Flat;

use Spatie\Fork\Fork;
use Betagento\ConcurrentIndexer\Model\Indexer\Flat\Action\Full as CustomizedFullAction;
use Betagento\ConcurrentIndexer\Model\Config;
use Magento\Indexer\Model\ProcessManager;

class AroundFullPlugin 
{
    
    public function __construct(
        protected CustomizedFullAction $customizedFullAction,
        protected Config $config,
        protected ProcessManager $processManager
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
        $processes = $this->customizedFullAction->execute($ids)->getProcesses();
        
        if ($this->config->isEnabled() && $this->config->getThreadCount() > 1 && $this->config->isCanBeParalleled() && !$this->config->isSetupMode() && PHP_SAPI == 'cli') {

            Fork::new()->concurrent($this->config->getThreadCount())->run(...$processes);
            return $subject;
        }
    
        $this->processManager->execute(new \ArrayIterator($processes));
        return $subject;
        
    }
}
