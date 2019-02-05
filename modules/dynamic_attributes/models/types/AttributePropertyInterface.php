<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use yii\db\ActiveRecordInterface;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * Interface AttributePropertyInterface
 * @package app\models\dynamic_attributes\types
 */
interface AttributePropertyInterface extends ActiveRecordInterface {

	/**
	 * Конфигурация поддерживаемых типом поисковых условий.
	 * @return array
	 */
	public static function conditionConfig():array;

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
	 * @param bool $formatted true возвращает форматированное значение, false - как есть
	 * @return mixed
	 */
	public static function getValue(int $attribute_id, int $property_id, int $user_id, bool $formatted = false);

	/**
	 * Записать в соответствующую таблицу значение свойства этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param mixed $value
	 * @return bool
	 */
	public static function setValue(int $attribute_id, int $property_id, int $user_id, $value):bool;

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
}