<?php
declare(strict_types = 1);

namespace app\modules\grades\models\references;

use yii\db\ActiveRecord;

/**
 * Сами грейды - это таблица id/имя(=id). Имя вообще сделано на будущее (если захотим иметь не "грейд 15" а "великий уровень" или что-то в этом духе.
 * Но вообще предполагается, что именно тут будет располагаться вся логика управления грейдами. Именно поэтому модуль не сделан справочником (а казалось бы).
 *
 *
 * @property int $id
 * @property string $name
 */
class RefGrades extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_salary_grades';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'string', 'max' => 255]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Name'
		];
	}
}
