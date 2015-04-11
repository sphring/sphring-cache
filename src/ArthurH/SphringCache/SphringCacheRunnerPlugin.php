<?php
namespace ArthurH\SphringCache;

use Arthurh\Sphring\Annotations\AnnotationsSphring\LoadContext;
use Arthurh\Sphring\Annotations\AnnotationsSphring\RootProject;
use ArthurH\SphringCache\CacheManager\AbstractCacheManager;

/**
 * Class SphringRunnerPlugin
 * @LoadContext
 * @RootProject(file="../../../")
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
