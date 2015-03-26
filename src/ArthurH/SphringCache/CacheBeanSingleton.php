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

namespace ArthurH\SphringCache;


use ArthurH\SphringCache\Bean\CacheBean;

class CacheBeanSingleton
{
    /**
     * @var CacheBean
     */
    private $cacheBean;
    /**
     * @var CacheBeanSingleton
     */
    private static $_instance = null;

    private function __construct()
    {
    }

    /**
     * @return CacheBeanSingleton
     */
    public static function getInstance()
    {

        if (is_null(self::$_instance)) {
            self::$_instance = new CacheBeanSingleton();
        }

        return self::$_instance;
    }

    /**
     * @return CacheBean
     */
    public function getCacheBean()
    {
        return $this->cacheBean;
    }

    /**
     * @param CacheBean $cacheManager
     */
    public function setCacheBean(CacheBean $cacheManager)
    {
        $this->cacheBean = $cacheManager;
    }


}
