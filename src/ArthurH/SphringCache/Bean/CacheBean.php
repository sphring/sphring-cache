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
use ArthurH\SphringCache\Exception\SphringCacheException;

class CacheBean extends AbstractBean
{

    /**
     * @var bool
     */
    private $cacheSphring = true;

    public function inject()
    {
        if (!is_subclass_of($this->class, AbstractCacheManager::class)) {
            throw new SphringCacheException("Class must be a instance of %s for bean '%'.",
                AbstractCacheManager::class, $this->id);
        }
        parent::inject();
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

}
