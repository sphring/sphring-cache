<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 12/04/2015
 */

namespace ArthurH\SphringCache\Annotation;


use Arhframe\Util\Folder;
use ArthurH\SphringCache\AbstractSphringCache;

class CacheableTest extends AbstractSphringCache
{

    public function testSimpleCacheable()
    {
        $foobean = $this->sphring->getBean('foobean');
        $timeBeforeCache = microtime();
        $foobean->testCacheable('testing');
        $timeBeforeCache = microtime() - $timeBeforeCache;
        $timeAfterCache = microtime();
        $value = $foobean->testCacheable('testing');
        $timeAfterCache = microtime() - $timeAfterCache;

        $this->assertGreaterThan($timeAfterCache, $timeBeforeCache);
        $this->assertEquals('testing', $value);
    }

    public function tearDown()
    {
        parent::tearDown();
        $cacheFolder = new Folder(__DIR__ . '/../cache');
        $cacheFolder->removeFiles('#.*#i', true);
        $cacheFolder->removeFolders('#.*#i', true);
        $cacheFolder->remove();
    }
}
