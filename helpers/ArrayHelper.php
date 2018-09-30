<?php
declare(strict_types=1);

namespace app\helpers;

use Closure;
use Throwable;

/**
 * Class ArrayHelper
 * @package app\helpers
 */
class ArrayHelper extends \yii\helpers\ArrayHelper {

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
	public static function loopArrayMerge($array1, $array2): array {
		return array_merge($array1, array_merge(...$array2));
	}
}