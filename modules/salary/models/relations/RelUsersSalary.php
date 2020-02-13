<?php
declare(strict_types = 1);

namespace app\modules\salary\models\relations;

use pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;
use pozitronik\core\traits\Relations;

/**
 * This is the model class for table "rel_users_salary".
 * Релейшен пользователя к зарплатным атрибутам, добавляемым модулем
 *
 * @property int $id
 * @property int $user_id
 * @property int $grade_id
 * @property int $premium_group_id
 * @property int $location_id
 */
class RelUsersSalary extends ActiveRecord {
	use Relations;
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_users_salary';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id'], 'required'],
			[['user_id', 'grade_id', 'premium_group_id', 'location_id'], 'integer'],
			[['user_id'], 'unique']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'grade_id' => 'Grade ID',
			'premium_group_id' => 'Premium Group ID',
			'location_id' => 'Location ID'
		];
	}
}
