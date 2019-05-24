<?php

namespace Dot\Cache;

use Dot\File;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\InvalidArgumentException;

class CacheFilePool extends CachePoolBase
{

	/**
	 * @var string
	 */
	protected $path;

	public function __construct(string $path)
	{
		$this->path = \rtrim($path, '/') . '/';
		if (!\is_dir($path)) {
			\mkdir($path, 0755, true);
		}
	}

	protected function _getFileName(string $key)
	{
		$key = \str_replace('/', '_', $key);

		return $this->path . $key . '.php';
	}

	/**
	 * Returns a Cache Item representing the specified key.
	 *
	 * This method must always return a CacheItemInterface object, even in case of
	 * a cache miss. It MUST NOT return null.
	 *
	 * @param string $key
	 *   The key for which to return the corresponding Cache Item.
	 *
	 * @throws InvalidArgumentException
	 *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
	 *   MUST be thrown.
	 *
	 * @return CacheItemInterface
	 *   The corresponding Cache Item.
	 */
	public function getItem($key)
	{
		$fileName = $this->_getFileName($key);
		if (false && \file_exists($fileName)) {
			$raw = File::read($fileName);
			$raw = \substr($raw, 47, -5);
			$raw = \unserialize($raw);

			$value = $raw['value'];
			$expires = $raw['expires'];
		} else {
			$value = null;
			$expires = null;
		}

		return new CacheItem($key, $value, $expires);
	}

	/**
	 * Confirms if the cache contains specified cache item.
	 *
	 * Note: This method MAY avoid retrieving the cached value for performance reasons.
	 * This could result in a race condition with CacheItemInterface::get(). To avoid
	 * such situation use CacheItemInterface::isHit() instead.
	 *
	 * @param string $key
	 *   The key for which to check existence.
	 *
	 * @throws InvalidArgumentException
	 *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
	 *   MUST be thrown.
	 *
	 * @return bool
	 *   True if item exists in the cache, false otherwise.
	 */
	public function hasItem($key): bool{
		return $this->getItem($key)->isHit();
	}

	/**
	 * {@inheritdoc}
	 */
	public function clear(): bool
	{
		$files = \glob($this->path, '*');
		$count = 0;
		foreach ($files as $file) {
			if ('.' === $file || '..' === $file) {
				continue;
			}

			\unlink($file);
			$count++;
		}

		return !!$count;
	}

	/**
	 * Removes the item from the pool.
	 *
	 * @param string $key
	 *   The key to delete.
	 *
	 * @throws InvalidArgumentException
	 *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
	 *   MUST be thrown.
	 *
	 * @return bool
	 *   True if the item was successfully removed. False if there was an error.
	 */
	public function deleteItem($key): bool
	{
		$file = $this->_getFileName($key);
		if (\file_exists($file)) {
			return \unlink($file);
		}

		return false;
	}

	/**
	 * Persists a cache item immediately.
	 *
	 * @param CacheItemInterface $item
	 *   The cache item to save.
	 *
	 * @return bool
	 *   True if the item was successfully persisted. False if there was an error.
	 */
	public function save(CacheItemInterface $item): bool
	{
		$file = $this->_getFileName($item->getKey());

		$expiration = &$item->expiration;
		$content = [
			'value'   => $item->get(),
			'expires' => $expiration ? $expiration->getTimestamp() : null
		];

		$header = '<?php header(\'HTTP/1.0 404 Not Found\'); die; /*';
		$footer = '*/ ?>';
		$content = \serialize($content);

		$content = $header . $content . $footer;

		$isAlright = File::write($file, $content);
		$item->isHit($isAlright);

		return true;
	}
}