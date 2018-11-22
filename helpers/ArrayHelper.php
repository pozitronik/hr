<?php
declare(strict_types = 1);

namespace app\helpers;

use Closure;
use Throwable;
use yii\helpers\ArrayHelper as YiiArrayHelper;

/**
 * Class ArrayHelper
 * @package app\helpers
 */
class ArrayHelper extends YiiArrayHelper {

	/**
	 * Расширенная функция, может кидать исключение или выполнять замыканьице
	 * @param array|object $array
	 * @param array|Closure|string $key
	 * @param null|Throwable|Closure $default
	 * @return mixed
	 * @throws Throwable
	 */
	public static function getValue($array, $key, $default = null) {
		$result = parent::getValue($array, $key, $default);
		if ($result === $default) {
			if ($default instanceof Closure) {
				return $default($array, $key);
			}
			if ($default instanceof Throwable) {
				throw $default;
			}
		}
		return $result;
	}

	/**
	 * Шорткат для мержа массива с массивом, полученным в цикле
	 * @see https://github.com/kalessil/phpinspectionsea/blob/master/docs/performance.md#slow-array-function-used-in-loop
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 */
	public static function loopArrayMerge(array $array1, array $array2):array {
		return array_merge($array1, array_merge(...$array2));
	}

	/**
	 * Ищет значение в многомерном массиве, если находит его, то возвращает массив со всеми ключами до этого элемента
	 * @param array $array
	 * @param mixed $search
	 * @param array $keys
	 * @return array
	 */
	public static function array_find_deep(array $array, $search, array $keys = []):array {
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$sub = self::array_find_deep($value, $search, array_merge($keys, [$key]));
				if (count($sub)) {
					return $sub;
				}
			} elseif ($value === $search) {
				return array_merge($keys, [$key]);
			}
		}

		return [];
	}

	/**
	 * Removes duplicate values from an array with multidimensional support
	 * @param array $array
	 * @param int $sort_flags
	 * @return array
	 */
	public static function array_unique(array $array, int $sort_flags = SORT_STRING):array {
		foreach ($array as &$val) {
			if (is_array($val)) {
				$val = self::array_unique($val, $sort_flags);
			} else {
				return array_unique($array);
			}
		}
		return $array;
	}

	/**
	 * Возвращает разницу между двумя ассоциативными массивами по их ключам.
	 * В отличие от array_diff_key вложенные массивы считает данными
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 */
	public static function diff_keys(array $array1, array $array2):array {
		$result = [];
		foreach ($array1 as $key => $value) {
			if (!array_key_exists($key, $array2)) $result[$key] = $value;
		}
		foreach ($array2 as $key => $value) {
			if (!array_key_exists($key, $array1)) $result[$key] = $value;
		}
		return $result;
	}

	/**
	 * Рекурсивно объединяет массивы так, что существующие ключи обновляются, новые ключи - добавляются.
	 * Пример:
	 * ArrayHelper::merge_recursive(['10' => ['5' => [3, 2]]], ['10' => ['6' => [4, 7]]]);
	 * вернёт:
	 * ['10' => ['5' => [3, 2], '6' => [4, 7]]
	 *
	 * @param array $array1
	 * @param array $array2
	 * @param array|null $_
	 * @return array
	 */
	public static function merge_recursive(array $array1, array $array2, array $_ = null):array {
		$arrays = func_get_args();
		$merged = [];
		while ($arrays) {
			$array = array_shift($arrays);
			if (!$array) continue;
			/** @var array $array */
			foreach ($array as $key => $value)
				if (is_array($value) && array_key_exists($key, $merged) && is_array($merged[$key])) {
					$merged[$key] = self::merge_recursive($merged[$key], $value);
				} else {
					$merged[$key] = $value;
				}
		}
		return $merged;
	}

	/**
	 * Аналог ArrayHelper::map склеивающий значения нескольких аттрибутов
	 * @param array $array
	 * @param string $id
	 * @param array $concat_attributes
	 * @param string $separator
	 * @return array
	 * @throws Throwable
	 */
	public static function cmap($array, $id, $concat_attributes = [], $separator = ' '):array {
		$result = [];
		foreach ($array as $element) {
			$key = self::getValue($element, $id);
			$value = [];
			foreach ($concat_attributes as $el) {
				$value[] = self::getValue($element, $el);
			}
			$result[$key] = implode($separator, $value);
		}

		return $result;
	}

}