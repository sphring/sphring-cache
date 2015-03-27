<?php
namespace ArthurH\SphringCache;

use ArthurH\SphringCache\CacheManager\AbstractCacheManager;

/**
 * Class SphringRunnerPlugin
 * @LoadContext
 * @RootProject(../../../)
 */
class SphringCacheRunnerPlugin extends \Arthurh\Sphring\Runner\SphringRunner
{
    /**
     * @var AbstractCacheManager
     */
    private $cacheManager;

    /**
     * @return AbstractCacheManager
     */
    public function getCacheManager()
    {
        return $this->cacheManager;
    }

    /**
     * @param AbstractCacheManager $cacheManager
     */
    public function setCacheManager(AbstractCacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }


}
