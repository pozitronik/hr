<?php
declare(strict_types = 1);

namespace app\models\relations;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "rel_users_groups".
 *
 * @property int $user_id Сотрудник
 * @property int $group_id Рабочая группа
 * @property int $user_role_id Роль сотрудника в группе
 */
class RelUsersGroups extends ActiveRecord {
	use Relations;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_users_groups';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id', 'group_id'], 'required'],
			[['user_id', 'group_id', 'user_role_id'], 'integer'],
			[['user_id', 'group_id'], 'unique', 'targetAttribute' => ['user_id', 'group_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'user_id' => 'Сотрудник',
			'group_id' => 'Рабочая группа',
			'user_role_id' => 'Роль сотрудника в группе'
		];
	}

}
