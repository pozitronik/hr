<?php
declare(strict_types = 1);

namespace app\models\relations;

use yii\db\ActiveRecord;
use RuntimeException;
/**
 * This is the model class for table "rel_users_groups_roles".
 *
 * @property int $user_group_id ID связки пользователь/группа
 * @property int $role Роль
 */
class RelUsersGroupsRoles extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_users_groups_roles';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_group_id', 'role'], 'required'],
			[['user_group_id', 'role'], 'integer'],
			[['user_group_id', 'role'], 'unique', 'targetAttribute' => ['user_group_id', 'role']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'user_group_id' => 'ID связки пользователь/группа',
			'role' => 'Роль'
		];
	}

	/**
	 * Добавляет пользователю роль в группу
	 * @param int $role
	 * @param int $group
	 * @return bool
	 */
	public static function setRoleInGroup($role, $group):bool {
		/*Связь пользователя в группе уже есть*/
		$rel = RelUsersGroups::find()->where(['group_id' => $group])->one();
		if ($rel) {
			$relUsersGroupsRoles = new self(['user_group_id' => $rel->id, 'role' => $role]);
			return $relUsersGroupsRoles->save();
		}
		throw new RuntimeException("Нельзя добавить роль пользователя для группы, в которой его нет");
	}
}
