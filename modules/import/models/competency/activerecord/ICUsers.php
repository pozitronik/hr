<?php
declare(strict_types = 1);

namespace app\modules\import\models\competency\activerecord;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_competency_users".
 *
 * @property int $id
 * @property string $name Имя сотрудника
 * @property int $hr_user_id id в системе
 */
class ICUsers extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_competency_users';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['hr_user_id'], 'integer'],
			[['name'], 'string', 'max' => 255]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Имя сотрудника',
			'hr_user_id' => 'id в системе'
		];
	}
}
