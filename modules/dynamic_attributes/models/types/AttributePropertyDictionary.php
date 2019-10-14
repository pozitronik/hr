<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\models\DynamicAttributePropertyAggregation;
use app\modules\dynamic_attributes\widgets\attribute_field_dictionary\DictionaryWidget;
use Exception;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * Class AttributePropertyDictionary
 * @package app\modules\dynamic_attributes\models\types
 * Словарь ключ-значение, прототипируем, например, для вывода
 */
class AttributePropertyDictionary implements AttributePropertyInterface {

	/**
	 * Конфигурация поддерживаемых типом поисковых условий.
	 * @return array
	 */
	public static function conditionConfig():array {
		return [];//не поддерживает поиск
	}

	/**
	 * Конфигурация поддерживаемых типом агрегаторов
	 * @return array
	 */
	public static function aggregationConfig():array {
		return [DynamicAttributePropertyAggregation::AGGREGATION_UNSUPPORTED];//не поддерживает агрегаторы, используется только для вывода статистики
	}

	/**
	 * Поиск соответствующей записи по подходящим параметрам
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 */
	public static function getRecord(int $attribute_id, int $property_id, int $user_id):void {
	}

	/**
	 * Вернуть из соответствующей таблицы значение свойства этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param bool $formatted true возвращает форматированное значение, false - как есть
	 * @return mixed
	 */
	public static function loadValue(int $attribute_id, int $property_id, int $user_id, bool $formatted = false) {
		return null;
	}

	/**
	 * Записать в соответствующую таблицу значение свойства этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param mixed $value
	 * @return bool
	 */
	public static function saveValue(int $attribute_id, int $property_id, int $user_id, $value):bool {
		return false;
	}

	/**
	 * Рендер поля просмотра значения свойства
	 * @param array $config Опциональные параметры виджета/поля
	 * @return string
	 * @throws Exception
	 */
	public static function viewField(array $config = []):string {
		return DictionaryWidget::widget($config);
	}

	/**
	 * Функция отдаёт форму поля для редактирования значения свойства
	 * @param ActiveForm $form
	 * @param DynamicAttributeProperty $property
	 * @return ActiveField
	 */
	public static function editField(ActiveForm $form, DynamicAttributeProperty $property):ActiveField {
		return null;
	}

	/**
	 * Прототип: устанавливает значение свойства
	 * @param $value
	 */
	public function setValue($value):void {

	}

	/**
	 * Прототип: возвращает значение свойства
	 * @return mixed
	 */
	public function getValue() {
		return null;
	}

	/**
	 * Применяет агрегатор к набору значений атрибутов
	 * @param self[] $models -- набор значений атрибутов
	 * @param int $aggregation -- выбранный агрегатор
	 * @param bool $dropNullValues -- true -- отфильтровать пустые значения из набора
	 * @return DynamicAttributePropertyAggregation -- результат агрегации в модели
	 */
	public static function applyAggregation(array $models, int $aggregation, bool $dropNullValues = false):?DynamicAttributePropertyAggregation {
		return null;
	}
}