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
	public const AGGREGATION_MEDIAN = 9;
	public const AGGREGATION_FREQUENCY = 10;


	public const AGGREGATION_LABELS = [
		self::AGGREGATION_AVG => 'Среднее арифметическое',
		self::AGGREGATION_HARMONIC => 'Среднее гармоническое',
		self::AGGREGATION_MODA => 'Наиболее распространённое значение',
		self::AGGREGATION_AVG_TRUNC => 'Среднее усечённое (60%)',
		self::AGGREGATION_COUNT => 'Количество значений',
		self::AGGREGATION_MIN => 'Минимальное значение',
		self::AGGREGATION_MAX => 'Максимальное значение',
		self::AGGREGATION_SUM => 'Сумма всех значений',
		self::AGGREGATION_MEDIAN => 'Медиана',
		self::AGGREGATION_FREQUENCY => 'Частотное распределение'
	];

	public const AGGREGATION_HINTS = [
		self::AGGREGATION_AVG => 'Сумма всех значений, делённая на их количество',
		self::AGGREGATION_HARMONIC => 'Корректное усреднение измеряемых величин',
		self::AGGREGATION_MODA => 'Значение, встречающееся в выборке наиболее часто',
		self::AGGREGATION_AVG_TRUNC => 'Отбрасываем по 20% наименьших и наибольших значений, по остатку считаем среднее',
		self::AGGREGATION_COUNT => 'Количество значений',
		self::AGGREGATION_MIN => 'Минимальное значение',
		self::AGGREGATION_MAX => 'Максимальное значение',
		self::AGGREGATION_SUM => 'Сумма всех значений',
		self::AGGREGATION_MEDIAN => 'Половина выборки меньше медианного значения, другая половина - больше',
		self::AGGREGATION_FREQUENCY => 'Статистика по частоте всех значений'
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
	 * @return float|null
	 */
	public static function AggregateIntAvg(array $values, bool $dropNullValues = false):?float {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		$summary = self::AggregateIntSum($values, $dropNullValues);
		return count($values)?$summary / count($values):null;
	}

	/**
	 * @param int[] $values
	 * @param bool $dropNullValues
	 * @return float|null
	 */
	public static function AggregateIntMedian(array $values, bool $dropNullValues = false):?float {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		if (0 === $count = count($values)) return null;
		sort($values, SORT_NUMERIC);
		$middleVal = (int)floor(($count - 1) / 2); // find the middle value, or the lowest middle value
		if ($count % 2) { // odd number, middle is the median
			$median = $values[$middleVal];
		} else { // even number, calculate avg of 2 medians
			$low = $values[$middleVal];
			$high = $values[$middleVal + 1];
			$median = (($low + $high) / 2);
		}
		return $median;
	}

	/**
	 * @param int[] $values
	 * @return float|null
	 */
	public static function AggregateIntHarmonic(array $values):?float {
		$num_args = count($values);
		$values = ArrayHelper::filterValues($values, ['', false, null, 0]);
		$sum = 0;
		foreach ($values as $iValue) {
			$sum += 1 / $iValue;
		}
		return (0 === $sum)?INF:$num_args / $sum;
	}

	/**
	 * @param int[] $values
	 * @param bool $dropNullValues
	 * @return int|null
	 * @throws Throwable
	 */
	public static function AggregateIntModa(array $values, bool $dropNullValues = true):?int {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		$modaArray = array_count_values(array_map(static function($value) {
			return null === $value?'':(int)$value;
		}, $values));
		if ($dropNullValues) unset ($modaArray['']);

		$maxValue = count($modaArray)?max($modaArray):null;
		return (int)array_search($maxValue, $modaArray);//наиболее часто встречаемое значение
	}

	/**
	 * @param int[] $values
	 * @param bool $dropNullValues
	 * @param int $truncPercent -- процент отбрасываемых минимальных и максимальных значений
	 * @return float|null
	 */
	public static function AggregateIntAvgTrunc(array $values, bool $dropNullValues = false, $truncPercent = 20):?float {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		sort($values, SORT_NUMERIC);
		$truncCount = (int)((count($values) * $truncPercent) / 100);
		$values = array_slice($values, $truncCount, -$truncCount, true);
		return self::AggregateIntAvg($values, $dropNullValues);
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
	 * @return float|null
	 */
	public static function AggregateIntMin(array $values, bool $dropNullValues = false):?float {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		return count($values)?min($values):null;
	}

	/**
	 * @param int[] $values
	 * @param bool $dropNullValues
	 * @return float|null
	 */
	public static function AggregateIntMax(array $values, bool $dropNullValues = false):?float {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		return count($values)?max($values):null;
	}

	/**
	 * @param int[] $values
	 * @param bool $dropNullValues
	 * @return float|null
	 */
	public static function AggregateIntSum(array $values, bool $dropNullValues = false):?float {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		return array_sum($values);
	}

	/**
	 * Возвращает массив частоты распределений значений
	 * @param array $values
	 * @param bool $dropNullValues
	 * @return array
	 */
	public static function FrequencyDistribution(array $values, bool $dropNullValues = true):array {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		$frequencies = [];
		foreach ($values as $key => $value) {
			if (!isset($frequencies[$value])) {
				$frequencies[$value] = [
					'id' => $key,
					'value' => $value,
					'frequency' => 1
				];
			} else {
				$frequencies[$value]['frequency']++;
			}
		}
		return $frequencies;
	}

}