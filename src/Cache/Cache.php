<?php

namespace Dot\Cache;

use Psr\Cache\CacheItemPoolInterface;

class Cache {
    protected $pool;

    function __construct(CacheItemPoolInterface $pool){
        $this->pool = $pool;
    }

    function get(string $key){
        $cached = $this->pool->getItem($key);

        return $cached ? $cached->get() : null;
    }

    function set(string $key, $value, $time = null){
        $item = new CacheItem($key, $value, $time);
        $this->pool->save($item);
    }

    function out(string $key){
        return $this->pool->deleteItem($key);
    }

    function reset(){
        // todo: make this
        return $this->pool->deleteItems([]);
    }
}