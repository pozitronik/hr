<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models;

/**
 * Class DynamicAttributePropertyAggregation
 * Все аггрегаторы по умолчанию работают на всём массиве данных. Отсеивание null-значений указываем дополнительным параметром.
 * @package app\modules\dynamic_attributes\models
 */
class DynamicAttributePropertyAggregation {
	public const AGGREGATION_AVG = 1;//Среднее арифметическое
	public const AGGREGATION_HARMONIC = 2;//среднее гармоническое
	public const AGGREGATION_MODA = 3;//наиболее распространённое значение
	public const AGGREGATION_AVG_TRUNC = 4;//среднее усечённое
	public const AGGREGATION_COUNT = 5;//количество значений
	public const AGGREGATION_MIN = 6;//минимальное значение
	public const AGGREGATION_MAX = 7;//максимальное значение
	public const AGGREGATION_SUM = 8;//сумма всех значений
}