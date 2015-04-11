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
use ArthurH\SphringCache\CacheAnnotation\Cacheable;

/**
 * Class Foo
 * @package Arthurh\Sphring\FakeBean
 */
class Foo
{
    private $test = "testFoo";
    /**
     * @var Bar
     */
    private $bar;

    public function __construct()
    {

    }

    /**
     * @Cacheable()
     */
    public function testCacheable()
    {

    }

    /**
     * @return string
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param string $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * @return Bar
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param Bar $bar
     */
    public function setBar(Bar $bar)
    {
        $this->bar = $bar;
    }

}
