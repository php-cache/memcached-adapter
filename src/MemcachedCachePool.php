<?php

/*
 * This file is part of php-cache\mencached-adapter package.
 *
 * (c) 2015-2015 Aaron Scherer <aequasi@gmail.com>, Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cache\Adapter\Memcached;

use Cache\Adapter\Common\AbstractCachePool;
use Psr\Cache\CacheItemInterface;

/**
 * @author Aaron Scherer <aequasi@gmail.com>
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class MemcachedCachePool extends AbstractCachePool
{
    /**
     * @type \Memcached
     */
    private $cache;

    /**
     * @param \Memcached $cache
     */
    public function __construct(\Memcached $cache)
    {
        $this->cache = $cache;
    }

    protected function fetchObjectFromCache($key)
    {
        return $this->cache->get($key);
    }

    protected function clearAllObjectsFromCache()
    {
        return $this->cache->flush();
    }

    protected function clearOneObjectFromCache($key)
    {
        if ($this->cache->delete($key)) {
            return true;
        }

        // Return true if key not found
        return $this->cache->getResultCode() === \Memcached::RES_NOTFOUND;
    }

    protected function storeItemInCache($key, CacheItemInterface $item, $ttl)
    {
        if ($ttl === null) {
            $ttl = 0;
        }

        return $this->cache->set($key, $item, $ttl);
    }
}
