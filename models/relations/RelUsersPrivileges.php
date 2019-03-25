<?php
declare(strict_types = 1);

namespace app\models\relations;

use app\models\core\ActiveRecordExtended;

/**
 * This is the model class for table "rel_users_privileges".
 *
 * @property int $id
 * @property int $user_id
 * @property int $privilege_id
 */
class RelUsersPrivileges extends ActiveRecordExtended {
	use Relations;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_users_privileges';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id', 'privilege_id'], 'required'],
			[['user_id', 'privilege_id'], 'integer'],
			[['user_id', 'privilege_id'], 'unique', 'targetAttribute' => ['user_id', 'privilege_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'privilege_id' => 'Privilege ID'
		];
	}
}
