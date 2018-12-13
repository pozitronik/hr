<?php
declare(strict_types = 1);

namespace app\models\dynamic_attributes\types;

use yii\db\ActiveRecordInterface;

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
	 * @return mixed
	 */
	public static function getValue(int $attribute_id, int $property_id, int $user_id);

	/**
	 * Записать в соответствующую таблицу значение свойства этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param mixed $value
	 * @return mixed
	 */
	public static function setValue(int $attribute_id, int $property_id, int $user_id, $value);
}