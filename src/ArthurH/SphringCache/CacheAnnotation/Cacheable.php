<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 11/04/2015
 */

namespace ArthurH\SphringCache\CacheAnnotation;

/**
 * @Annotation
 * @Target({"METHOD"})
 * Class Cacheable
 * @package ArthurH\SphringCache\CacheAnnotation
 */
class Cacheable
{
    /**
     * @var string
     */
    public $value;
    /**
     * @var string
     */
    public $cacheManager;
    /**
     * @var string
     */
    public $condition;
    /**
     * @var string
     */
    public $key;
    /**
     * @var string
     */
    public $keyGenerator;
    /**
     * @var int
     */
    public $lifetime = 0;
    /**
     * @var string
     */
    public $unless;
}
