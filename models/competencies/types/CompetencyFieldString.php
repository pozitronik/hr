<?php
declare(strict_types = 1);

namespace app\models\competencies\types;


/**
 * This is the model class for table "sys_competencies_string".
 *
 * @property int $id
 * @property int $competency_id ID компетенции
 * @property int $field_id ID поля
 * @property int $user_id ID пользователя
 * @property string $value Значение
 */
class CompetencyFieldString extends CompetencyFieldDefault {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_competencies_string';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['competency_id', 'field_id', 'user_id'], 'required'],
			[['competency_id', 'field_id', 'user_id'], 'integer'],
			[['value'], 'string'],
			[['competency_id', 'field_id', 'user_id'], 'unique', 'targetAttribute' => ['competency_id', 'field_id', 'user_id']]
		];
	}

}
