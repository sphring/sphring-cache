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
    public function __construct()
    {
        parent::__construct();
        $this->contextFileTest = __DIR__ . '/../Resources/mainCacheTest.yml';
    }

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
        $this->contextCacheFile->remove();
        $this->annotationFolder->removeFiles('#.*#i', true);
        $this->annotationFolder->removeFolders('#.*#i', true);
        $this->annotationFolder->remove();
        $cacheFolder = new Folder(__DIR__ . '/../cache');
        $cacheFolder->removeFiles('#.*#i', true);
        $cacheFolder->removeFolders('#.*#i', true);
        $cacheFolder->remove();
    }
}
