<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models;

use app\modules\dynamic_attributes\models\types\AttributePropertyInterface;
use yii\base\Model;

/**
 * Class DynamicAttributePropertyAggregation
 * Все агрегаторы по умолчанию работают на всём массиве данных. Отсеивание null-значений указываем дополнительным параметром.
 * @package app\modules\dynamic_attributes\models
 * @property string $type -- тип свойства, полученного в результате агрегации (может не совпадать с типами аггрегирующих свойств, навпример вернётся строка или процент или null)
 * @property AttributePropertyInterface $value -- значение свойства, полученного в результате агрегации
 */
class DynamicAttributePropertyAggregation extends Model {
	public const AGGREGATION_AVG = 1;//Среднее арифметическое
	public const AGGREGATION_HARMONIC = 2;//среднее гармоническое
	public const AGGREGATION_MODA = 3;//наиболее распространённое значение
	public const AGGREGATION_AVG_TRUNC = 4;//среднее усечённое
	public const AGGREGATION_COUNT = 5;//количество значений
	public const AGGREGATION_MIN = 6;//минимальное значение
	public const AGGREGATION_MAX = 7;//максимальное значение
	public const AGGREGATION_SUM = 8;//сумма всех значений

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
	 * @return AttributePropertyInterface
	 */
	public function getValue():AttributePropertyInterface {
		return $this->_value;
	}

	/**
	 * @param AttributePropertyInterface $value
	 */
	public function setValue(AttributePropertyInterface $value):void {
		$this->_value = $value;
	}

}