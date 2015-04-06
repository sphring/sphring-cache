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
use Arhframe\Util\Folder;
use Arthurh\Sphring\Sphring;
use ArthurH\SphringCache\Enum\SphringCacheEnum;

class SphringCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Sphring
     */
    private $sphring;

    /**
     * @var File
     */
    private $contextCacheFile;
    /**
     * @var File
     */
    private $beanCacheFile;
    /**
     * @var Folder
     */
    private $proxiesFolder;

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

    public function setUp()
    {
        $contextFile = new File(__DIR__ . '/Resources/mainSimpleTest.yml');
        $this->contextCacheFile = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR
            . sprintf(SphringCacheEnum::CACHE_FILE_CONTEXT, $contextFile->getHash('md5')));
        $this->beanCacheFile = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR
            . sprintf(SphringCacheEnum::CACHE_FILE_BEAN, $contextFile->getHash('md5')));
        $this->proxiesFolder = new Folder(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER_PROXIES);
        $this->sphring = new Sphring(__DIR__ . '/Resources/mainSimpleTest.yml');
        $this->sphring->setComposerLockFile(__DIR__ . '/Resources/composer.lock');
        $this->sphring->loadContext();
    }

    public function tearDown()
    {
        $this->contextCacheFile->remove();
        $this->beanCacheFile->remove();
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
        $this->assertCount(3, $files);
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
