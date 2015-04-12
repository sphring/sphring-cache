<?php
namespace ArthurH\SphringCache;

use Arhframe\Util\File;
use Arhframe\Util\Folder;
use Arthurh\Sphring\Sphring;
use ArthurH\SphringCache\Enum\SphringCacheEnum;

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
class AbstractSphringCache extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->contextFileTest = __DIR__ . '/Resources/mainSimpleTest.yml';
    }

    protected $contextFileTest;
    /**
     * @var Sphring
     */
    protected $sphring;

    /**
     * @var File
     */
    protected $contextCacheFile;
    /**
     * @var File
     */
    protected $beanCacheFile;
    /**
     * @var Folder
     */
    protected $proxiesFolder;
    /**
     * @var Folder
     */
    protected $annotationFolder;

    public function setUp()
    {
        $contextFile = new File($this->contextFileTest);
        $this->contextCacheFile = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR
            . sprintf(SphringCacheEnum::CACHE_FILE_CONTEXT, $contextFile->getHash('md5')));
        $this->beanCacheFile = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR
            . sprintf(SphringCacheEnum::CACHE_FILE_BEAN, $contextFile->getHash('md5')));
        $this->proxiesFolder = new Folder(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER_PROXIES);
        $this->annotationFolder = new Folder(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER_ANNOTATIONS);
        $this->sphring = new Sphring($this->contextFileTest);
        $this->sphring->setComposerLockFile(__DIR__ . '/Resources/composer.lock');
        $this->sphring->loadContext();
    }

    public function tearDown()
    {
        $this->contextCacheFile->remove();
        $this->beanCacheFile->remove();
        $this->annotationFolder->removeFiles('#.*#i', true);
        $this->annotationFolder->removeFolders('#.*#i', true);
        $this->annotationFolder->remove();
    }
}
