<?php
declare(strict_types = 1);

namespace app\models\competencies\types;


/**
 * This is the model class for table "sys_competencies_range".
 *
 * @property int $id
 * @property int $competency_id ID компетенции
 * @property int $field_id ID поля
 * @property int $user_id ID пользователя
 * @property int $value_min Нижний порог значения
 * @property int $value_max Верхний порог значения
 */
class CompetencyFieldRange extends CompetencyFieldDefault  {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_competencies_range';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['competency_id', 'field_id', 'user_id'], 'required'],
			[['competency_id', 'field_id', 'user_id', 'value_min', 'value_max'], 'integer'],
			[['competency_id', 'field_id', 'user_id'], 'unique', 'targetAttribute' => ['competency_id', 'field_id', 'user_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'competency_id' => 'ID компетенции',
			'field_id' => 'ID поля',
			'user_id' => 'ID пользователя',
			'value_min' => 'Нижний порог значения',
			'value_max' => 'Верхний порог значения'
		];
	}
}
