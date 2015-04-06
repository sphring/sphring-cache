<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 27/03/2015
 */

namespace ArthurH\SphringCache\GlobalEvent;


use Arhframe\Util\File;
use Arhframe\Util\Folder;
use Arthurh\Sphring\Model\SphringGlobal;
use ArthurH\SphringCache\CacheManager\AbstractCacheManager;
use ArthurH\SphringCache\Enum\SphringCacheEnum;
use ArthurH\SphringCache\SphringCacheRunnerPlugin;

class CacheSphringFinished extends SphringGlobal
{
    /**
     * @var AbstractCacheManager
     */
    protected $cacheManager;

    /**
     * @var int
     */
    private $time;

    /**
     * @return mixed
     */
    public function run()
    {
        $cacheManager = SphringCacheRunnerPlugin::getInstance()->getCacheManager();
        if (empty($cacheManager)) {
            return;
        }
        $this->cacheManager = $cacheManager;
        $this->cacheSphringContext();
        $this->cacheSphringBean();
        $this->removeProxies();
    }

    private function cacheSphringContext()
    {
        $origFile = new File($this->sphring->getYamlarh()->getFilename());
        $cacheFileContext = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR
            . sprintf(SphringCacheEnum::CACHE_FILE_CONTEXT, $origFile->getHash('md5')));
        if (!$this->cacheManager->isCacheSphringContext()) {
            if ($cacheFileContext->isFile()) {
                $cacheFileContext->remove();
            }
            return;
        }
        $origFile = new File($this->sphring->getYamlarh()->getFilename());
        if ($cacheFileContext->isFile() && $origFile->getTime() == $cacheFileContext->getTime()) {
            return;
        }
        $context = $this->sphring->getContext();
        $origFile = $this->sphring->getYamlarh()->getFilename();
        $time = $this->getTime();
        touch($origFile, $time);
        $cacheFileContext->setContent(serialize($context));
        touch($cacheFileContext->absolute(), $time);
    }

    private function cacheSphringBean()
    {
        $origFile = new File($this->sphring->getYamlarh()->getFilename());
        $cacheFileBean = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR
            . sprintf(SphringCacheEnum::CACHE_FILE_BEAN, $origFile->getHash('md5')));
        if (!$this->cacheManager->isCacheSphringBean()) {
            if ($cacheFileBean->isFile()) {
                $cacheFileBean->remove();
            }
            return;
        }
        $origFile = new File($this->sphring->getYamlarh()->getFilename());
        if ($cacheFileBean->isFile() && $origFile->getTime() == $cacheFileBean->getTime()) {
            return;
        }
        $beans = $this->sphring->getBeansObject();
        $origFile = $this->sphring->getYamlarh()->getFilename();
        $time = $this->getTime();
        touch($origFile, $time);
        $cacheFileBean->setContent(serialize($beans));
        touch($cacheFileBean->absolute(), $time);
    }

    private function removeProxies()
    {
        if ($this->cacheManager->isCacheSphringProxies()) {
            return;
        }
        $proxiesFolder = new Folder(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER_PROXIES);
        $proxiesFolder->removeFiles('#.*#i', true);
        $proxiesFolder->remove();
    }

    /**
     * @return AbstractCacheManager
     */
    public function getCacheManager()
    {
        return $this->cacheManager;
    }

    /**
     * @param AbstractCacheManager $cacheManager
     */
    public function setCacheManager(AbstractCacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @return int
     */
    public function getTime()
    {
        if ($this->time === null) {
            $this->time = time();
        }
        return $this->time;
    }

}
