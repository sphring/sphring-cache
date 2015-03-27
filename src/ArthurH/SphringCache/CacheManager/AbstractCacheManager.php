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
    private $sphring;

    /**
     * @var bool
     */
    private $cacheSphring = true;

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

}
