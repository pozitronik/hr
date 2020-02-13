<?php
declare(strict_types = 1);

namespace app\modules\privileges\models\relations;

use yii\db\ActiveRecord;
use pozitronik\core\traits\Relations;
use app\modules\privileges\models\Privileges;
use app\modules\users\models\Users;

/**
 * This is the model class for table "rel_users_privileges".
 *
 * @property int $id
 * @property int $user_id
 * @property int $privilege_id
 */
class RelUsersPrivileges extends ActiveRecord {
	use Relations;

	/**
	 * {@inheritDoc}
	 */
	public function historyRules():array {
		return [
			'attributes' => [
				'privilege_id' => [Privileges::class => 'name'],
				'user_id' => [Users::class => 'username']
			]
		];
	}

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
			'user_id' => 'Пользователь',
			'privilege_id' => 'Привилегия'
		];
	}
}
