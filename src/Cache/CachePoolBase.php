<?php

namespace Dot\Cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

abstract class CachePoolBase implements CacheItemPoolInterface
{
    protected $_deferItems = [];

    /**
     * {@inheritdoc}
     */
    public function getItems(array $keys = array())
    {
        $items = [];
        foreach($keys as $key){
            $items[$key] = $this->getItem($key);
        }

        return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem($key): bool
    {
        return $this->getItem($key)->isHit();
    }

    /**
     * {@inheritdoc}
     */
    abstract public function clear(): bool;

    /**
     * {@inheritdoc}
     */
    abstract public function deleteItem($key) : bool;

    /**
     * {@inheritdoc}
     */
    public function deleteItems(array $keys) : bool
    {
        $count = 0;
        foreach($keys as $key){
            $this->deleteItem($key) && $count++;
        }
        return $count === count($keys);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function save(CacheItemInterface $item) : bool;

    /**
     * {@inheritdoc}
     */
    public function saveDeferred(CacheItemInterface $item) : bool
    {
        $this->_deferItems[] = &$item;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function commit() : bool
    {
        $items = &$this->_deferItems;
        foreach($items as $key => $item){
            $this->save($item);
            if($item->isHit()){
                unset($items[$key]);
            }
        }
        return empty($items);
    }
}