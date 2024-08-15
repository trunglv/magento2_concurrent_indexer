# Magento Concurrent/Multithreading Indexer

## Purposes:
Magento 2 already supports multithreading for some indexer processes, but not all of them—specifically, the Product Flat Indexer. The purpose of this module is to:
#### 1. Enable the Product Flat Indexer to run concurrently.
#### 2. Replace the default multithreading functionality in Magento Core with the spatie/fork module. Although I haven't benchmarked it yet, I have a feeling that spatie/fork is faster and seems to be more modern and professional in terms of coding.

## Config
In etc/env.php, we should define MAGE_INDEXER_THREADS_COUNT (Magento2 Core) and a new one, BETA_CONCURRENT_INDEXER_THREADS_ENABLE.

### !!!!If you want to use "spatie/fork" , you should enable BETA_CONCURRENT_INDEXER_THREADS_ENABLE

```
...
'MAGE_INDEXER_THREADS_COUNT' => 3,
'BETA_CONCURRENT_INDEXER_THREADS_ENABLE' => 1
```

## How multithreading Indexer is implemented by Magento2 Core

#### CLASS \Magento\Catalog\Model\Indexer\Category\Product\Action
```
/**
     * Run reindexation
     *
     * @return void
     */
    protected function reindex(): void
    {
        $userFunctions = [];

        foreach ($this->storeManager->getStores() as $store) {
            if ($this->getPathFromCategoryId($store->getRootCategoryId())) {
                $userFunctions[$store->getId()] = function () use ($store) {
                    $this->reindexStore($store);
                };
            }
        }

        $this->processManager->execute($userFunctions);
    }
```

#### CLASS \Magento\Indexer\Model\ProcessManager

```
/**
     * Execute user functions
     *
     * @param \Traversable $userFunctions
     */
    public function execute($userFunctions)
    {
        if ($this->threadsCount > 1 && $this->isCanBeParalleled() && !$this->isSetupMode() && PHP_SAPI == 'cli') {
            $this->multiThreadsExecute($userFunctions);
        } else {
            $this->simpleThreadExecute($userFunctions);
        }
    }
```
