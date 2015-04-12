<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 11/04/2015
 */

namespace ArthurH\SphringCache\KeyGenerator;


class DefaultKeyGenerator implements KeyGenerator
{

    /**
     * @param $element
     * @return string
     */
    public function generate($element)
    {
        if (is_object($element)) {
            return spl_object_hash($element);
        }
        return md5(serialize($element));
    }
}
