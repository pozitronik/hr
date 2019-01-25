<?php

namespace app\modules\import\models\competency\activerecord;

use Yii;

/**
 * This is the model class for table "import_competency_users".
 *
 * @property int $id
 * @property string $name Имя сотрудника
 * @property int $hr_user_id id в системе
 */
class ICUsers extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'import_competency_users';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['name'], 'required'],
			[['hr_user_id'], 'integer'],
			[['name'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'name' => 'Имя сотрудника',
			'hr_user_id' => 'id в системе',
		];
	}
}
