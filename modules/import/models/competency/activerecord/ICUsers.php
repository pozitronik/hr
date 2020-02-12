<?php
declare(strict_types = 1);

namespace app\modules\import\models\competency\activerecord;

use pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_competency_users".
 *
 * @property int $id
 * @property string $name Имя сотрудника
 * @property int $hr_user_id id в системе
 * @property int $domain
 */
class ICUsers extends ActiveRecord {
	use ARExtended;
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
			[['name'], 'unique'],
			[['hr_user_id'], 'integer'],
			[['name'], 'string', 'max' => 255],
			['domain', 'integer'], ['domain', 'required']
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
