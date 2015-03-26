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
use ArthurH\SphringCache\Bean\CacheBean;
use ArthurH\SphringCache\CacheManager\AbstractCacheManager;
use ArthurH\SphringCache\Exception\SphringCacheException;

abstract class AbstractSphringCacheAnnotation extends AbstractAopAnnotation
{

    /**
     * @var AbstractCacheManager
     */
    protected $cacheManager;

    /**
     * @throws SphringCacheException
     */
    public function run()
    {
        $cacheBean = $this->getBean();
        if (!($cacheBean instanceof CacheBean)) {
            throw new SphringCacheException("Error for bean '%s' cache must be a instance of %s for annotation '%'.",
                $this->bean->getId(), CacheBean::class, $this::getAnnotationName());
        }
        $this->cacheManager = $cacheBean->getObject();
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
