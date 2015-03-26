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


use Arthurh\Sphring\Sphring;

class SphringCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $sphring = new Sphring(__DIR__ . '/Resources/mainSimpleTest.yml');
        $sphring->setComposerLockFile(__DIR__ . '/Resources/composer.lock');
        $sphring->loadContext();
        $foo = $sphring->getBean('foobean');
        $foo->testCacheable();
        
    }
}
