<?php
namespace Betagento\ConcurrentIndexer\Model\Indexer\Flat\Action;

class Full extends \Magento\Catalog\Model\Indexer\Product\Flat\AbstractAction
{
    /**
     * Contains processes will be executed
     *
     * @var array<callable>
     */
    protected $_processes = [];

    /**
     * Summary of _executed
     * @var bool
     */
    protected $_executed = false;

    /**
     * Summary of execute
     * @param array<int>|null $ids
     * @return static
     */
    public function execute($ids = null)
    {
        $processes = [];
        foreach ($this->_storeManager->getStores() as $store) {
            $processes[] = fn () => $this->_executeForStore($store);
        }
        $this->_processes = $processes;
        $this->_executed = true;
        return $this;
    }

    /**
     * Summary of getProcesses
     * @return array<callable>
     */
    public function getProcesses()
    {
        if ($this->_executed) {
            return $this->_processes;
        }
        return [];
        
    }

    /**
     * Summary of _executeForStore
     * @param \Magento\Store\Api\Data\StoreInterface $store
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _executeForStore($store)
    {
        try {
            //echo 'Running for store '. $store->getName() . '....' . PHP_EOL;
            $this->_reindex($store->getId()); 
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
        }
    }
    
}