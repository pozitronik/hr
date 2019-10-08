<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models;

use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\Model;

/**
 * Class DynamicAttributePropertyAggregation
 * Все агрегаторы по умолчанию работают на всём массиве данных. Отсеивание null-значений указываем дополнительным параметром.
 * @package app\modules\dynamic_attributes\models
 * @property string $type -- тип свойства, полученного в результате агрегации (может не совпадать с типами аггрегирующих свойств, навпример вернётся строка или процент или null)
 * @property mixed $value -- значение свойства, полученного в результате агрегации
 */
class DynamicAttributePropertyAggregation extends Model {

	public const AGGREGATION_UNSUPPORTED = null;

	public const AGGREGATION_AVG = 1;//Среднее арифметическое
	public const AGGREGATION_HARMONIC = 2;//среднее гармоническое
	public const AGGREGATION_MODA = 3;//наиболее распространённое значение
	public const AGGREGATION_AVG_TRUNC = 4;//среднее усечённое
	public const AGGREGATION_COUNT = 5;//количество значений
	public const AGGREGATION_MIN = 6;//минимальное значение
	public const AGGREGATION_MAX = 7;//максимальное значение
	public const AGGREGATION_SUM = 8;//сумма всех значений

	public const AGGREGATION_LABELS = [
		self::AGGREGATION_AVG => 'Среднее арифметическое',
		self::AGGREGATION_HARMONIC => 'Среднее гармоническое',
		self::AGGREGATION_MODA => 'Наиболее распространённое значение',
		self::AGGREGATION_AVG_TRUNC => 'Среднее усечённое (60%)',
		self::AGGREGATION_COUNT => 'Количество значений',
		self::AGGREGATION_MIN => 'Минимальное значение',
		self::AGGREGATION_MAX => 'Максимальное значение',
		self::AGGREGATION_SUM => 'Сумма всех значений'
	];

	private $_type;
	private $_value;

	/**
	 * @return string
	 */
	public function getType():string {
		return $this->_type;
	}

	/**
	 * @param string $type
	 */
	public function setType(string $type):void {
		$this->_type = $type;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->_value;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value):void {
		$this->_value = $value;
	}

	/**
	 * @param int[] $values
	 * @param bool $dropNullValues
	 * @return int|null
	 */
	public static function AggregateIntAvg(array $values, bool $dropNullValues = false):?int {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		$summary = self::AggregateIntSum($values, $dropNullValues);
		return $summary / count($values);
	}

	/**
	 * @param int[] $values
	 * @param bool $dropNullValues
	 * @return int|null
	 */
	public static function AggregateIntHarmonic(array $values):?int {
		$num_args = count($values);
		$values = ArrayHelper::filterValues($values, ['', false, null, 0]);
		$sum = 0;
		foreach ($values as $iValue) {
			$sum += 1 / $iValue;
		}
		return $num_args / $sum;
	}

	/**
	 * @param int[] $values
	 * @param bool $dropNullValues
	 * @return int|null
	 * @throws Throwable
	 * @todo: требуется проверка
	 */
	public static function AggregateIntModa(array $values, bool $dropNullValues = false):?int {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		$modaArray = array_count_values($values);
		$maxValue = max($modaArray);
		//требуется проверка
		return array_search($maxValue, $modaArray);//наиболее часто встречаемое значение
	}

	/**
	 * @param int[] $values
	 * @param bool $dropNullValues
	 * @param int $truncPercent -- процент отбрасываемых минимальных и максимальных значений
	 * @return int|null
	 * @todo: требуется проверка
	 */
	public static function AggregateIntAvgTrunc(array $values, bool $dropNullValues = false, $truncPercent = 20):?int {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		sort($values, SORT_NUMERIC);
		$truncCount = (count($values) * $truncPercent) / 100;
		$values = array_slice($values, $truncCount, -$truncCount, true);
		return self::AggregateIntAvg($values);
	}

	/**
	 * @param int[] $values
	 * @param bool $dropNullValues
	 * @return int|null
	 */
	public static function AggregateIntCount(array $values, bool $dropNullValues = false):?int {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		return count($values);
	}

	/**
	 * @param int[] $values
	 * @param bool $dropNullValues
	 * @return int|null
	 */
	public static function AggregateIntMin(array $values, bool $dropNullValues = false):?int {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		return min($values);
	}

	/**
	 * @param int[] $values
	 * @param bool $dropNullValues
	 * @return int|null
	 */
	public static function AggregateIntMax(array $values, bool $dropNullValues = false):?int {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		return max($values);
	}

	/**
	 * @param int[] $values
	 * @param bool $dropNullValues
	 * @return int|null
	 */
	public static function AggregateIntSum(array $values, bool $dropNullValues = false):?int {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		return array_sum($values);
//		return array_reduce($values, static function($carry, $item) {
//			return $carry + $item;
//		});
	}

}