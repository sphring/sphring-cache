<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 26/03/2015
 */

namespace ArthurH\SphringCache\CacheAnnotation;


use Arthurh\Sphring\Model\Annotation\AopAnnotation\AbstractAopAnnotation;
use ArthurH\SphringCache\CacheManager\AbstractCacheManager;
use ArthurH\SphringCache\Exception\SphringCacheException;
use ArthurH\SphringCache\SphringCacheRunnerPlugin;

abstract class AbstractSphringCacheAnnotation extends AbstractAopAnnotation
{
    /**
     * @var AbstractCacheManager
     */
    private $cacheManager;

    /**
     * @throws SphringCacheException
     */
    public function run()
    {
        $cacheManager = SphringCacheRunnerPlugin::getInstance()->getCacheManager();
        if ($cacheManager === null) {
            return;
        }
        if (!($cacheManager instanceof AbstractCacheManager)) {
            throw new SphringCacheException("Error for bean '%s' cache must be a instance of %s for annotation '%'.",
                $this->bean->getId(), AbstractCacheManager::class, $this::getAnnotationName());
        }
        $this->cacheManager = $cacheManager;
    }

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
