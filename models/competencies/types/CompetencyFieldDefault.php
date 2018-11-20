<?php
declare(strict_types = 1);

namespace app\models\competencies\types;

use RuntimeException;
use yii\db\ActiveRecord;

/** @noinspection UndetectableTableInspection */

/**
 * Class CompetencyFieldDefault
 * @package app\models\competencies\types
 *
 * @property int $competency_id ID компетенции
 * @property int $field_id ID поля
 * @property int $user_id ID пользователя
 * @property mixed $value
 */
class CompetencyFieldDefault extends ActiveRecord implements DataFieldInterface {

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		throw new RuntimeException('Не определено имя таблицы');
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'competency_id' => 'ID компетенции',
			'field_id' => 'ID поля',
			'user_id' => 'ID пользователя',
			'value' => 'Значение'
		];
	}

	/**
	 * Вернуть из соответствующей таблицы значение поля для этого поля этой компетенции этого юзера
	 * @param int $competency_id
	 * @param int $field_id
	 * @param int $user_id
	 * @return mixed
	 */
	public static function getValue(int $competency_id, int $field_id, int $user_id) {
		return self::find()->where(compact('competency_id', 'field_id', 'user_id'))->one();
	}

	/**
	 * Записать в соответствующую таблицу значение поля для этого поля этой компетенции этого юзера
	 * @param int $competency_id
	 * @param int $field_id
	 * @param int $user_id
	 * @param mixed $value
	 * @return mixed
	 */
	public static function setValue(int $competency_id, int $field_id, int $user_id, $value) {
		$value = new self(compact('competency_id', 'user_id', 'field_id', 'value'));
		return $value->save();
	}

}