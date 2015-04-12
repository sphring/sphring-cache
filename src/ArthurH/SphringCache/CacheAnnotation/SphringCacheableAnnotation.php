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


use Arthurh\Sphring\Exception\SphringAnnotationException;
use Arthurh\Sphring\Utils\ClassName;
use ArthurH\SphringCache\CacheManager\AbstractCacheManager;
use ArthurH\SphringCache\Exception\SphringCacheException;
use ArthurH\SphringCache\KeyGenerator\DefaultKeyGenerator;
use ArthurH\SphringCache\KeyGenerator\KeyGenerator;

class SphringCacheableAnnotation extends AbstractSphringCacheAnnotation
{
    /**
     * @return string
     */
    public static function getAnnotationName()
    {
        return ClassName::getShortName(Cacheable::class);
    }

    /**
     * @throws SphringCacheException
     */
    public function run()
    {
        parent::run();
        $cacheableAnnot = $this->getData();
        if (!($cacheableAnnot instanceof Cacheable)) {
            throw new SphringAnnotationException("Error in bean '%s' in class annotation: Annotation '%s' required to have a '%s' class.",
                $this->getBean()->getId(), get_class($this), Cacheable::class);
        }
        if ($cacheableAnnot->value === null) {
            throw new SphringAnnotationException("Error in bean '%s' in class annotation: Annotation '%s' required to have a value property in annotation.",
                $this->getBean()->getId(), get_class($this));
        }
        $keyGenerator = $this->getKeyGenerator($cacheableAnnot->keyGenerator);
        $cacheManager = $this->getCacheManagerFromAnnotation($cacheableAnnot->cacheManager);
        $condition = $cacheableAnnot->condition;
        if ($condition !== null && !$this->evaluateExpressionBoolean($condition)) {
            return;
        }
        $key = $this->generateKey($cacheableAnnot->value, $cacheableAnnot->key, $keyGenerator);
        if ($cacheManager->contains($key)) {
            $this->event->setData($cacheManager->fetch($key));
        }
        if (!$this->isAfterCall()) {
            return;
        }
        $unless = $cacheableAnnot->unless;
        if ($unless !== null && !$this->evaluateExpressionBoolean($unless)) {
            return;
        }
        $args = $this->getEvent()->getMethodArgs();
        $cacheManager->save($key, $args['#result'], $cacheableAnnot->lifetime);
    }

    private function isAfterCall()
    {
        $args = $this->getEvent()->getMethodArgs();
        return isset($args['#result']);
    }

    /**
     * @param $value
     * @param $key
     * @param KeyGenerator $keyGenerator
     * @return string
     */
    private function generateKey($value, $key, KeyGenerator $keyGenerator)
    {
        $finalKey = $value;
        $args = $this->getEvent()->getMethodArgs();
        if ($args === null) {
            return $finalKey;
        }
        $finalArgs = $args;
        if (isset($finalArgs['#result'])) {
            unset($finalArgs['#result']);
        }
        if ($key === null) {
            return $finalKey . '/' . $keyGenerator->generate($finalArgs);
        }
        return $finalKey . '/' . $keyGenerator->generate($this->evaluateExpression($key));
    }

    /**
     * @param KeyGenerator $keyGenerator
     * @return KeyGenerator
     * @throws SphringAnnotationException
     */
    private function getKeyGenerator($keyGenerator)
    {
        if ($keyGenerator === null) {
            return new DefaultKeyGenerator();
        }
        if (!class_exists($keyGenerator)) {
            throw new SphringAnnotationException("Error in bean '%s' in class annotation: Annotation '%s' not a valid KeyGenerator for '%s'.",
                $this->getBean()->getId(), get_class($this), $keyGenerator);
        }
        $keyGenerator = new $keyGenerator();
        if (!($keyGenerator instanceof KeyGenerator)) {
            throw new SphringAnnotationException("Error in bean '%s' in class annotation: Annotation '%s' not a valid KeyGenerator for '%s' it must implements '%s'.",
                $this->getBean()->getId(), get_class($this), $keyGenerator, KeyGenerator::class);
        }
        return $keyGenerator;
    }

    /**
     * @param $cacheManager
     * @return AbstractCacheManager
     * @throws SphringAnnotationException
     */
    private function getCacheManagerFromAnnotation($cacheManager)
    {
        if ($cacheManager === null) {
            return $this->getCacheManager();
        }
        if (!class_exists($cacheManager)) {
            throw new SphringAnnotationException("Error in bean '%s' in class annotation: Annotation '%s' not a valid CacheManager for '%s'.",
                $this->getBean()->getId(), get_class($this), $cacheManager);
        }
        $cacheManager = new $cacheManager();
        if (!($cacheManager instanceof AbstractCacheManager)) {
            throw new SphringAnnotationException("Error in bean '%s' in class annotation: Annotation '%s' not a valid CacheManager for '%s' it must implements '%s'.",
                $this->getBean()->getId(), get_class($this), $cacheManager, AbstractCacheManager::class);
        }
        return $cacheManager;
    }
}
