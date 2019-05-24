<?php

namespace Dot\Cache;

use Psr\Cache\CacheItemInterface;

class CacheItem implements CacheItemInterface
{

    private $key;
    private $value;
    protected $hit;
    public $expiration;

    public function __construct(string $key, $value = null, $time = null)
    {
        $this->key = $key;
        if ($value) {
            $this->set($value);
        }
        if ($time) {
            $this->expiresAt($time);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->hit ?
            $this->value :
            null;
    }

    /**
     * {@inheritdoc}
     */
    public function isHit()
    {
        return $this->hit;
    }

    /**
     * {@inheritdoc}
     */
    public function set($value)
    {
        $this->value = $value;
        $this->hit = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function expiresAt($expiration)
    {
        if ($expiration instanceof \DateTime) {
            $this->expiration = $expiration;
        } else if (is_int($expiration)) {
            $this->expiration = new \DateTime('now +' . $expiration . ' seconds');
        } else if ($expiration === null) {
            $this->expiration = new \DateTime('now +1 year');
        } else {
            // todo: change to phpdot error
            throw new \Error('Integer or \DateTime object expected');
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function expiresAfter($time)
    {
        $expiration = &$this->expiration;
        if ($time instanceof \DateInterval) {
            $expiration = new \DateTime;
            $expiration->add($time);
        } else if (is_int($time)) {
            $expiration = new \DateTime('now +' . $time . ' seconds');
        } else if ($time === null) {
            $this->expiration = new \DateTime('now +1 year');
        } else {
            // todo: change to phpdot error
            throw new \Error('Integer or \DateTime object expected');
        }
        return $this;
    }
}