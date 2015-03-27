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
use Arthurh\Sphring\Sphring;
use ArthurH\SphringCache\Enum\SphringCacheEnum;

class SphringCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testCachedContextFileLoaded()
    {
        $mockCache = new File(__DIR__ . '/Resources/cacheMock.cache');
        $contextFile = new File(__DIR__ . '/Resources/mainSimpleTest.yml');
        $time = time();
        $contextCacheFile = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR
            . sprintf(SphringCacheEnum::CACHE_FILE, $contextFile->getHash('md5')));
        $contextCacheFile->setContent($mockCache->getContent());
        touch($contextFile->absolute(), $time);
        touch($contextCacheFile->absolute(), $time);
        $sphring = new Sphring(__DIR__ . '/Resources/mainSimpleTest.yml');
        $sphring->setComposerLockFile(__DIR__ . '/Resources/composer.lock');
        $sphring->loadContext();
        $beanCache = $sphring->getBean('foobeancache');
        $this->assertNotNull($beanCache);
        $contextCacheFile->remove();
    }

    public function testCacheContextFile()
    {
        $sphring = new Sphring(__DIR__ . '/Resources/mainSimpleTest.yml');
        $sphring->setComposerLockFile(__DIR__ . '/Resources/composer.lock');
        $sphring->loadContext();
        $contextFile = new File(__DIR__ . '/Resources/mainSimpleTest.yml');
        $contextCacheFile = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR
            . sprintf(SphringCacheEnum::CACHE_FILE, $contextFile->getHash('md5')));
        $this->assertTrue($contextCacheFile->isFile());
        $contextCacheFile->remove();
    }
}
