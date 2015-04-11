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

namespace ArthurH\SphringCache\Enum;


use MyCLabs\Enum\Enum;

class SphringCacheEnum extends Enum
{
    const CACHE_FILE_CONTEXT = '.cache-sphring-%s';
    const CACHE_FILE_BEAN = '.cache-sphring-bean-%s';
    const CACHE_FOLDER = 'sphring';
    const CACHE_FOLDER_PROXIES = 'proxies';
    const CACHE_FOLDER_ANNOTATIONS = 'annotations';
}
