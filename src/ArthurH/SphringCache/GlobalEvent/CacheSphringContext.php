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

namespace ArthurH\SphringCache\GlobalEvent;


use Arhframe\Util\File;
use Arthurh\Sphring\Model\SphringGlobal;

class CacheSphringContext extends SphringGlobal
{
    const CACHE_FILE = '.cache-sphring-%s';

    /**
     * @var File
     */
    private $cacheFile;

    /**
     * @return mixed
     */
    public function run()
    {
        $origFile = new File($this->getSphring()->getYamlarh()->getFilename());
        $this->cacheFile = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            sprintf(CacheSphringContext::CACHE_FILE, $origFile->getHash('md5')));
        if (!$this->cacheFile->isFile()) {
            return;
        }
        $this->loadFromCache();
    }

    private function loadFromCache()
    {
        $origFile = new File($this->getSphring()->getYamlarh()->getFilename());
        if ($origFile->getTime() != $this->cacheFile->getTime()) {
            return;
        }
        $context = unserialize($this->cacheFile->getContent());
        $this->getSphring()->setContext($context);
    }

    /**
     * @return File
     */
    public function getCacheFile()
    {
        return $this->cacheFile;
    }

    /**
     * @param File $cacheFile
     */
    public function setCacheFile(File $cacheFile)
    {
        $this->cacheFile = $cacheFile;
    }


}
