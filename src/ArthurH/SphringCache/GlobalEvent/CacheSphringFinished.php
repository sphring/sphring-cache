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
    }

    private function cacheSphringContext()
    {
        $origFile = new File($this->sphring->getYamlarh()->getFilename());
        $cacheFile = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR
            . sprintf(SphringCacheEnum::CACHE_FILE, $origFile->getHash('md5')));
        if (!$this->cacheManager->isCacheSphring() && $cacheFile->isFile()) {
            $cacheFile->remove();
            return;
        } elseif (!$this->cacheManager->isCacheSphring()) {
            return;
        }
        $origFile = new File($this->sphring->getYamlarh()->getFilename());
        if ($cacheFile->isFile() && $origFile->getTime() == $cacheFile->getTime()) {
            return;
        }
        $context = $this->sphring->getContext();
        $origFile = $this->sphring->getYamlarh()->getFilename();
        $time = time();
        touch($origFile, $time);
        $cacheFile->setContent(serialize($context));
        touch($cacheFile->absolute(), $time);
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
}
