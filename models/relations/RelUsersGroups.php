<?php
declare(strict_types = 1);

namespace app\models\relations;

use app\modules\users\models\references\RefUserRoles;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rel_users_groups".
 *
 * @property int id
 * @property int $user_id Сотрудник
 * @property int $group_id Рабочая группа
 *
 * @property ActiveQuery|RelUsersGroupsRoles[] $relUsersGroupsRoles Связь с релейшеном к ролям в группе
 * @property ActiveQuery|RefUserRoles[] $refUserRoles Роли пользователя в группе, полученные через релейшен
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
			[['id', 'user_id', 'group_id'], 'integer'],
			[['user_id', 'group_id'], 'unique', 'targetAttribute' => ['user_id', 'group_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'Сотрудник',
			'group_id' => 'Рабочая группа',
			'user_role_id' => 'Роль сотрудника в группе'
		];
	}

	/**
	 * @return RelUsersGroupsRoles[]|ActiveQuery
	 */
	public function getRelUsersGroupsRoles() {
		return $this->hasMany(RelUsersGroupsRoles::class, ['user_group_id' => 'id']);
	}

	/**
	 * @return RefUserRoles[]|ActiveQuery
	 */
	public function getRefUserRoles() {
		return $this->hasMany(RefUserRoles::class, ['id' => 'role'])->via('relUsersGroupsRoles');
	}

}
