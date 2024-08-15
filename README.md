# magento2 modudel Concurrent Indexer

## Purposes:
Magento 2 already supports multithreading for some indexer processes, but not all of them—specifically, the Product Flat Indexer. The purpose of this module is to:
### 1.Enable the Product Flat Indexer to run concurrently.
### 2.Replace the default multithreading functionality in Magento Core with the spatie/fork module. Although I haven't benchmarked it yet, I have a feeling that spatie/fork is faster and seems to be more modern and professional in terms of coding.



