<?php
declare(strict_types = 1);

namespace app\models\competencies\types;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "sys_competencies_integer".
 *
 * @property int $id
 * @property int $competency_id ID компетенции
 * @property int $field_id ID поля
 * @property int $user_id ID пользователя
 * @property int $value Значение
 */
class CompetencyFiledInteger extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'sys_competencies_integer';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['competency_id', 'field_id', 'user_id'], 'required'],
			[['competency_id', 'field_id', 'user_id', 'value'], 'integer'],
			[['competency_id', 'field_id', 'user_id'], 'unique', 'targetAttribute' => ['competency_id', 'field_id', 'user_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'competency_id' => 'ID компетенции',
			'field_id' => 'ID поля',
			'user_id' => 'ID пользователя',
			'value' => 'Значение',
		];
	}
}
