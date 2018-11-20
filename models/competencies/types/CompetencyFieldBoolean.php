<?php
declare(strict_types = 1);

namespace app\models\competencies\types;


/**
 * This is the model class for table "sys_competencies_boolean".
 *
 * @property int $id
 * @property int $competency_id ID компетенции
 * @property int $field_id ID поля
 * @property int $user_id ID пользователя
 * @property int $value Значение
 */
class CompetencyFieldBoolean extends CompetencyFieldDefault  {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_competencies_boolean';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['competency_id', 'field_id', 'user_id'], 'required'],
			[['competency_id', 'field_id', 'user_id', 'value'], 'integer'],
			[['competency_id', 'field_id', 'user_id'], 'unique', 'targetAttribute' => ['competency_id', 'field_id', 'user_id']]
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
		return (null !== $model = self::find()->where(compact('competency_id', 'field_id', 'user_id'))->one())?$model->value:null;
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
