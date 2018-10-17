<?php
declare(strict_types = 1);

namespace app\models\relations;


/**
 * This is the model class for table "rel_users_groups_roles".
 *
 * @property int $user_group_id ID связки пользователь/группа
 * @property int $role Роль
 */
class RelUsersGroupsRoles extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'rel_users_groups_roles';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['user_group_id', 'role'], 'required'],
			[['user_group_id', 'role'], 'integer'],
			[['user_group_id', 'role'], 'unique', 'targetAttribute' => ['user_group_id', 'role']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'user_group_id' => 'ID связки пользователь/группа',
			'role' => 'Роль',
		];
	}
}
