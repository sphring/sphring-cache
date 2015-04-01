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
use Arhframe\Util\Folder;
use Arthurh\Sphring\Model\SphringGlobal;
use Arthurh\Sphring\ProxyGenerator\ProxyGenerator;
use ArthurH\SphringCache\Enum\SphringCacheEnum;
use ProxyManager\Configuration;
use ProxyManager\Factory\AccessInterceptorValueHolderFactory;

class CacheSphringContext extends SphringGlobal
{

    /**
     * @var File
     */
    private $cacheFile;

    /**
     * @return mixed
     */
    public function run()
    {
        $this->cacheProxy();
        $origFile = new File($this->getSphring()->getYamlarh()->getFilename());
        $this->cacheFile = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR .
            sprintf(SphringCacheEnum::CACHE_FILE, $origFile->getHash('md5')));
        if (!$this->cacheFile->isFile()) {
            return;
        }
        $this->loadContextFromCache();
    }

    private function cacheProxy()
    {
        $proxiesFolder = new Folder(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER_PROXIES);
        $proxiesFolder->create();
        $proxyManagerConfiguration = new Configuration();
        $proxyManagerConfiguration->setProxiesTargetDir($proxiesFolder->absolute());
        $proxyFactory = new AccessInterceptorValueHolderFactory($proxyManagerConfiguration);
        ProxyGenerator::getInstance()->setProxyFactory($proxyFactory);
        spl_autoload_register($proxyManagerConfiguration->getProxyAutoloader());
    }

    private function loadContextFromCache()
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
