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

namespace ArthurH\SphringCache\Bean;


use Arthurh\Sphring\Model\Bean\AbstractBean;
use ArthurH\SphringCache\CacheManager\AbstractCacheManager;
use ArthurH\SphringCache\CacheManagerSingleton;
use ArthurH\SphringCache\Exception\SphringCacheException;
use ArthurH\SphringCache\SphringCacheRunnerPlugin;

class CacheBean extends AbstractBean
{

    /**
     * @var bool
     */
    private $cacheSphring = true;
    /**
     * @var bool
     */
    private $cacheSphringProxies = true;
    /**
     * @var bool
     */
    private $cacheSphringContext = true;
    /**
     * @var bool
     */
    private $cacheSphringBean = false;

    public function inject()
    {
        if (!is_subclass_of($this->class, AbstractCacheManager::class)) {
            throw new SphringCacheException("Class must be a instance of %s for bean '%'.",
                AbstractCacheManager::class, $this->id);
        }
        parent::inject();
        $this->object->setCacheSphring($this->cacheSphring);
        $this->object->setCacheSphringProxies($this->cacheSphringProxies);
        $this->object->setCacheSphringContext($this->cacheSphringContext);
        $this->object->setCacheSphringBean($this->cacheSphringBean);
        SphringCacheRunnerPlugin::getInstance()->setCacheManager($this->object);
    }

    public function getValidBeanFile()
    {
        return __DIR__ . '/../Validation/cachebean.yml';
    }

    /**
     * @return boolean
     */
    public function isCacheSphring()
    {
        return $this->cacheSphring;
    }

    /**
     * @param boolean $cacheSphring
     */
    public function setCacheSphring($cacheSphring)
    {
        $this->cacheSphring = (bool)$cacheSphring;
    }

    /**
     * @return boolean
     */
    public function isCacheSphringProxies()
    {
        return $this->cacheSphringProxies;
    }

    /**
     * @param boolean $cacheSphringProxies
     */
    public function setCacheSphringProxies($cacheSphringProxies)
    {
        $this->cacheSphringProxies = $cacheSphringProxies;
    }

    /**
     * @return boolean
     */
    public function isCacheSphringContext()
    {
        return $this->cacheSphringContext;
    }

    /**
     * @param boolean $cacheSphringContext
     */
    public function setCacheSphringContext($cacheSphringContext)
    {
        $this->cacheSphringContext = $cacheSphringContext;
    }

    /**
     * @return boolean
     */
    public function isCacheSphringBean()
    {
        return $this->cacheSphringBean;
    }

    /**
     * @param boolean $cacheSphringBean
     */
    public function setCacheSphringBean($cacheSphringBean)
    {
        $this->cacheSphringBean = $cacheSphringBean;
    }

}
