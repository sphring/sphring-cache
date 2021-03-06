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


use Arhframe\Util\File;
use ArthurH\SphringCache\Enum\SphringCacheEnum;

class SphringCacheTest extends AbstractSphringCache
{


    public function testCachedContextFileLoaded()
    {
        $mockCache = new File(__DIR__ . '/Resources/cacheMock.cache');
        $contextFile = new File(__DIR__ . '/Resources/mainSimpleTest.yml');
        $time = time();
        $this->contextCacheFile->setContent($mockCache->getContent());
        touch($contextFile->absolute(), $time);
        touch($this->contextCacheFile->absolute(), $time);
        $this->setUp();
        $beanCache = $this->sphring->getBean('foobeancache');
        $this->assertNotNull($beanCache);
    }

    public function testCacheContextFile()
    {
        $contextFile = new File(__DIR__ . '/Resources/mainSimpleTest.yml');
        $this->contextCacheFile = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR
            . sprintf(SphringCacheEnum::CACHE_FILE_CONTEXT, $contextFile->getHash('md5')));
        $this->assertTrue($this->contextCacheFile->isFile());
    }

    public function testCacheProxies()
    {
        $files = $this->proxiesFolder->getFiles('#.*.php$#i');
        $this->assertCount(5, $files);
    }

    public function testCacheAnnotations()
    {
        $files = $this->annotationFolder->getFiles('#.*$#i', true);
        $this->assertCount(29, $files);
    }

    public function testCacheBean()
    {
        $contextFile = new File(__DIR__ . '/Resources/mainSimpleTest.yml');
        $this->beanCacheFile = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR
            . sprintf(SphringCacheEnum::CACHE_FILE_BEAN, $contextFile->getHash('md5')));
        $beans = unserialize($this->beanCacheFile->getContent());
        $foobean = $beans['foobean']->getObject();
        $this->assertEquals('testFoo', $foobean->getTest());
        $this->assertEquals('testBar', $foobean->getBar()->getTest());
        $this->sphring->clear();

        $this->assertEmpty($this->sphring->getBeansObject());
        $this->sphring->loadContext();
        $foobean = $this->sphring->getBean('foobean');
        $this->assertEquals('testFoo', $foobean->getTest());
        $this->assertEquals('testBar', $foobean->getBar()->getTest());
    }
}
