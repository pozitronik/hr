<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\models\DynamicAttributePropertyAggregation;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * Interface AttributePropertyInterface
 * @package app\models\dynamic_attributes\types
 */
interface AttributePropertyInterface {

	/**
	 * Конфигурация поддерживаемых типом поисковых условий.
	 * @return array
	 */
	public static function conditionConfig():array;

	/**
	 * Конфигурация поддерживаемых типом агрегаторов
	 * @return array
	 */
	public static function aggregationConfig():array;

	/**
	 * Поиск соответствующей записи по подходящим параметрам
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 */
	public static function getRecord(int $attribute_id, int $property_id, int $user_id);

	/**
	 * Вернуть из соответствующей таблицы значение свойства этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @return mixed
	 */
	public static function loadValue(int $attribute_id, int $property_id, int $user_id);

	/**
	 * Записать в соответствующую таблицу значение свойства этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param mixed $value
	 * @return bool
	 */
	public static function saveValue(int $attribute_id, int $property_id, int $user_id, mixed $value):bool;

	/**
	 * Рендер поля просмотра значения свойства
	 * @param array $config Опциональные параметры виджета/поля
	 * @return string
	 */
	public static function viewField(array $config = []):string;

	/**
	 * Функция отдаёт форму поля для редактирования значения свойства
	 * @param ActiveForm $form
	 * @param DynamicAttributeProperty $property
	 * @return ActiveField
	 */
	public static function editField(ActiveForm $form, DynamicAttributeProperty $property):ActiveField;

	/**
	 * Применяет агрегатор к набору значений атрибутов
	 * @param self[] $models -- набор значений атрибутов
	 * @param int $aggregation -- выбранный агрегатор
	 * @param bool $dropNullValues -- true -- отфильтровать пустые значения из набора
	 * @return DynamicAttributePropertyAggregation|null -- результат агрегации в модели
	 */
	public static function applyAggregation(array $models, int $aggregation, bool $dropNullValues = false):?DynamicAttributePropertyAggregation;

	/**
	 * @param mixed $value -- value to be formatted
	 * @return mixed -- formatted output
	 */
	public static function format(mixed $value);
}