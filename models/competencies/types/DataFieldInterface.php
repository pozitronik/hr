<?php
declare(strict_types = 1);

namespace app\models\competencies\types;

use yii\db\ActiveRecordInterface;

/**
 * Interface DataFieldInterface
 * @package app\models\competencies\types
 */
interface DataFieldInterface extends ActiveRecordInterface {

	/**
	 * Поиск соответствующей записи по подходящим параметрам
	 * @param int $competency_id
	 * @param int $field_id
	 * @param int $user_id
	 */
	public static function getRecord(int $competency_id, int $field_id, int $user_id);

	/**
	 * Вернуть из соответствующей таблицы значение поля для этого поля этой компетенции этого юзера
	 * @param int $competency_id
	 * @param int $field_id
	 * @param int $user_id
	 * @return mixed
	 */
	public static function getValue(int $competency_id, int $field_id, int $user_id);

	/**
	 * Записать в соответствующую таблицу значение поля для этого поля этой компетенции этого юзера
	 * @param int $competency_id
	 * @param int $field_id
	 * @param int $user_id
	 * @param mixed $value
	 * @return mixed
	 */
	public static function setValue(int $competency_id, int $field_id, int $user_id, $value);
}