<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 15/10/2014
 */

namespace ArthurH\SphringCache\FakeBean;

/**
 * Class Foo
 * @package Arthurh\Sphring\FakeBean
 * @TestClassInstantiate
 */
class Foo
{

    public function __construct()
    {

    }

    /**
     * @Cacheable
     */
    public function testCacheable()
    {

    }
}
