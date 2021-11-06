<?php
declare(strict_types = 1);

namespace app\modules\export\models;

use DateInterval;
use DateTime;
use Exception;
use Psr\SimpleCache\InvalidArgumentException as CacheInvalidArgumentException;
use Traversable;
use Yii;
use yii\base\Component;
use Psr\SimpleCache\CacheInterface;
use yii\base\InvalidArgumentException;
use yii\caching\Cache;
use yii\di\Instance;

/**
 * Class SimpleCacheAdapter
 * @package app\modules\export\models
 */
class SimpleCacheAdapter extends Component implements CacheInterface {
	private const INVALID_KEY_CHARACTER = '{}()/\@:';

	/**
	 * @var Cache|null
	 */
	private $cache;

	/**
	 * @throws \yii\base\InvalidConfigException
	 */
	public function init() {
		parent::init();

		if (null === $this->cache) {
			$this->cache = Yii::$app->cache;
		} else {
			$this->cache = Instance::ensure($this->cache, Cache::class);
		}

	}

	/**
	 * Cache::get() return false if the value is not in the cache or expired, but PSR-16 return $default(null)
	 *
	 * @param string $key
	 * @param null $default
	 * @return bool|mixed|null
	 */
	public function get($key, $default = null) {
		$this->assertValidKey($key);
		$data = $this->cache->get($key);

		if (false === $data) return $default;

		if (null === $data) return false;

		return $data;
	}

	/**
	 * {@inheritDoc}
	 */
	public function set($key, $value, $ttl = null):bool {
		$this->assertValidKey($key);

		if (false === ($duration = $this->toSeconds($ttl))) return $this->delete($key);

		if (null === $value) return $this->delete($key);

		// case FALSE to null so we can detect that if
		// the cache miss/expired or it did set the FALSE value into cache
		$value = false === $value?null:$value;
		return $this->cache->set($key, $value, $duration);
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($key):bool {
		$this->assertValidKey($key);

		return !$this->has($key) || $this->cache->delete($key);
	}

	/**
	 * {@inheritDoc}
	 */
	public function clear():bool {
		return $this->cache->flush();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMultiple($keys, $default = null) {
		if (!$keys instanceof Traversable && !is_array($keys)) {
			throw new InvalidArgumentException('Invalid keys: '.var_export($keys, true).'. Keys should be an array or Traversable of strings.');
		}

		$data = [];
		foreach ($keys as $key) {
			$data[$key] = $this->get($key, $default);
		}

		return $data;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setMultiple($values, $ttl = null):bool {
		if (!$values instanceof Traversable && !is_array($values)) {
			throw new InvalidArgumentException('Invalid keys: '.var_export($values, true).'. Keys should be an array or Traversable of strings.');
		}

		$pairs = [];
		foreach ($values as $key => $value) {
			$this->assertValidKey($key);
			$pairs[$key] = $value;
		}

		$res = true;
		foreach ($pairs as $key => $value) {
			$res = $res && $this->set($key, $value, $ttl);
		}

		return $res;
	}

	/**
	 * @param iterable $keys
	 * @return bool
	 * @throws CacheInvalidArgumentException
	 */
	public function deleteMultiple($keys):bool {
		if ($keys instanceof Traversable) {
			$keys = iterator_to_array($keys, false);
		} elseif (!is_array($keys)) {
			throw new InvalidArgumentException('Invalid keys: '.var_export($keys, true).'. Keys should be an array or Traversable of strings.');
		}

		$res = true;
		array_map(function($key) use (&$res) {
			$res = $res && $this->delete($key);
		}, $keys);

		return $res;
	}

	/**
	 * {@inheritDoc}
	 */
	public function has($key):bool {
		$this->assertValidKey($key);
		return $this->cache->exists($key);
	}

	/**
	 * @param $key
	 */
	private function assertValidKey($key):void {
		if (!is_string($key)) {
			throw new InvalidArgumentException('Invalid key: '.var_export($key, true).'. Key should be a string.');
		}

		if ('' === $key) {
			throw new InvalidArgumentException('Invalid key. Key should not be empty.');
		}

		// valid key according to PSR-16 rules
		$invalid = preg_quote(static::INVALID_KEY_CHARACTER, '/');
		if (preg_match('/['.$invalid.']/', $key)) {
			throw new InvalidArgumentException(
				'Invalid key: '.$key.'. Contains (a) character(s) reserved '.
				'for future extension: '.static::INVALID_KEY_CHARACTER
			);
		}
	}

	/**
	 * @param $ttl
	 */
	private function assertValidTtl($ttl):void {
		if (null !== $ttl && !is_int($ttl) && !$ttl instanceof DateInterval) {
			$error = 'Invalid time: '.serialize($ttl).'. Must be integer or instance of DateInterval.';
			throw new InvalidArgumentException($error);
		}
	}

	/**
	 * @param $ttl
	 * @return false|int
	 * @throws Exception
	 */
	private function toSeconds($ttl) {
		$this->assertValidTtl($ttl);

		if (null === $ttl) {
			// 0 means forever in Yii 2 cache
			return 0;
		}

		if (is_int($ttl)) {
			$sec = $ttl;
		} else {
			$sec = (new DateTime())->add($ttl)->getTimestamp() - (new DateTime())->getTimestamp();
		}

		return $sec > 0?$sec:false;
	}
}
