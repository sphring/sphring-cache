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

namespace ArthurH\SphringCache\CacheAnnotation;


use Arhframe\Util\File;
use Arthurh\Sphring\Model\Annotation\AopAnnotation\AbstractAopAnnotation;
use ArthurH\SphringCache\Bean\CacheBean;
use ArthurH\SphringCache\CacheManager\AbstractCacheManager;
use ArthurH\SphringCache\Exception\SphringCacheException;
use ArthurH\SphringCache\GlobalEvent\CacheSphringContext;

abstract class AbstractSphringCacheAnnotation extends AbstractAopAnnotation
{
    /**
     * @var CacheBean
     */
    protected $cacheBean;
    /**
     * @var AbstractCacheManager
     */
    protected $cacheManager;

    /**
     * @throws SphringCacheException
     */
    public function run()
    {
        $cacheBean = $this->getBean();
        if (!($cacheBean instanceof CacheBean)) {
            throw new SphringCacheException("Error for bean '%s' cache must be a instance of %s for annotation '%'.",
                $this->bean->getId(), CacheBean::class, $this::getAnnotationName());
        }
        $this->cacheBean = $this->getBean();
        $this->cacheManager = $cacheBean->getObject();
        $this->cacheSphringContext();
    }

    private function cacheSphringContext()
    {
        $sphring = $this->bean->getSphringEventDispatcher()->getSphring();
        $contextRoot = $sphring->getContextRoot();
        $cacheFile = new File($contextRoot . DIRECTORY_SEPARATOR . CacheSphringContext::CACHE_FILE);
        if (!$this->cacheBean->isCacheSphring() && $cacheFile->isFile()) {
            $cacheFile->remove();
            return;
        } elseif (!$this->cacheBean->isCacheSphring()) {
            return;
        }
        $origFile = new File($sphring->getYamlarh()->getFilename());
        if ($origFile->getTime() == $cacheFile->getTime()) {
            return;
        }
        $context = $sphring->getContext();
        $origFile = $sphring->getYamlarh()->getFilename();
        $time = time();
        touch($origFile, $time);
        $cacheFile->setContent(serialize($context));
        touch(realpath($cacheFile->absolute()), $time);
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
     * @return CacheBean
     */
    public function getCacheBean()
    {
        return $this->cacheBean;
    }

    /**
     * @param CacheBean $cacheBean
     */
    public function setCacheBean($cacheBean)
    {
        $this->cacheBean = $cacheBean;
    }

}
