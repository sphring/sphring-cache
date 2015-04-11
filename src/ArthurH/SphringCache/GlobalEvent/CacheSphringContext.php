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
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;
use ProxyManager\Configuration;
use ProxyManager\Factory\AccessInterceptorValueHolderFactory;

class CacheSphringContext extends SphringGlobal
{

    /**
     * @var File
     */
    private $cacheFileContext;
    /**
     * @var File
     */
    private $cacheFileBean;

    /**
     * @return mixed
     */
    public function run()
    {
        $this->cacheProxy();
        $this->loadContextFromCache();
        $this->loadBeansFromCache();
        $this->loadAnnotationFromCache();
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
        $this->cacheFileContext = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR .
            sprintf(SphringCacheEnum::CACHE_FILE_CONTEXT, $origFile->getHash('md5')));
        if (!$this->cacheFileContext->isFile()) {
            return;
        }
        $origFile = new File($this->getSphring()->getYamlarh()->getFilename());
        if ($origFile->getTime() != $this->cacheFileContext->getTime()) {
            return;
        }
        $context = unserialize($this->cacheFileContext->getContent());
        $this->getSphring()->setContext($context);
    }

    private function loadAnnotationFromCache()
    {
        $annotationsFolder = new Folder(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER_ANNOTATIONS);
        $annotationsFolder->create();
        $reader = new CachedReader(
            new AnnotationReader(),
            new FilesystemCache($annotationsFolder->absolute())
        );
        $sphringBoot = $this->sphring->getSphringEventDispatcher()->getSphringBoot();
        $sphringBoot->getSphringAnnotationReader()->setReader($reader);
    }

    private function loadBeansFromCache()
    {

        $origFile = new File($this->getSphring()->getYamlarh()->getFilename());
        $this->cacheFileContext = new File(sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            SphringCacheEnum::CACHE_FOLDER . DIRECTORY_SEPARATOR .
            sprintf(SphringCacheEnum::CACHE_FILE_BEAN, $origFile->getHash('md5')));
        if (!$this->cacheFileContext->isFile()) {
            return;
        }
        $origFile = new File($this->getSphring()->getYamlarh()->getFilename());
        if ($origFile->getTime() != $this->cacheFileContext->getTime()) {
            return;
        }
        $beans = unserialize($this->cacheFileContext->getContent());
        $this->getSphring()->setBeansObject($beans);

        $proxies = [];

        //fast table writing
        $key = array_keys($beans);
        $size = sizeOf($key);
        for ($i = 0; $i < $size; $i++) {
            $bean = $beans[$key[$i]];
            $proxies[$bean->getId()] = $bean->getObject();
        }

        $this->getSphring()->setProxyBeans($proxies);
    }

    /**
     * @return File
     */
    public function getCacheFileContext()
    {
        return $this->cacheFileContext;
    }

    /**
     * @param File $cacheFileContext
     */
    public function setCacheFileContext(File $cacheFileContext)
    {
        $this->cacheFileContext = $cacheFileContext;
    }

    /**
     * @return File
     */
    public function getCacheFileBean()
    {
        return $this->cacheFileBean;
    }

    /**
     * @param File $cacheFileBean
     */
    public function setCacheFileBean($cacheFileBean)
    {
        $this->cacheFileBean = $cacheFileBean;
    }


}
