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

namespace ArthurH\SphringCache\CacheManager;


use Arthurh\Sphring\Sphring;

abstract class AbstractCacheManager
{
    /**
     * @var Sphring
     */
    protected $sphring;

    /**
     * @var bool
     */
    protected $cacheSphring = true;
    /**
     * @var bool
     */
    protected $cacheSphringProxies = true;
    /**
     * @var bool
     */
    protected $cacheSphringContext = true;
    /**
     * @var bool
     */
    private $cacheSphringAnnotation = true;
    /**
     * @var bool
     */
    protected $cacheSphringBean = false;

    /**
     * @return Sphring
     */
    public function getSphring()
    {
        return $this->sphring;
    }

    /**
     * @param Sphring $sphring
     */
    public function setSphring(Sphring $sphring)
    {
        $this->sphring = $sphring;
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
        $this->cacheSphring = (boolean)$cacheSphring;
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

    /**
     * @return boolean
     */
    public function isCacheSphringAnnotation()
    {
        return $this->cacheSphringAnnotation;
    }

    /**
     * @param boolean $cacheSphringAnnotation
     */
    public function setCacheSphringAnnotation($cacheSphringAnnotation)
    {
        $this->cacheSphringAnnotation = $cacheSphringAnnotation;
    }

}
