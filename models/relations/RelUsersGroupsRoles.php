<?php
declare(strict_types = 1);

namespace app\models\relations;

use app\models\core\ActiveRecordExtended;
use app\modules\groups\models\Groups;
use app\modules\users\models\references\RefUserRoles;
use yii\db\ActiveQuery;
use app\helpers\ArrayHelper;

/**
 * This is the model class for table "rel_users_groups_roles".
 *
 * @property int $user_group_id ID связки пользователь/группа
 * @property ActiveQuery|RelUsersGroups[] $relUsersGroups
 * @property int $role Роль
 */
class RelUsersGroupsRoles extends ActiveRecordExtended {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_users_groups_roles';
	}

	/**
	 * {@inheritDoc}
	 */
	public function historyRules():array {
		return [
			'attributes' => [
				'role' => [RefUserRoles::class => 'name'],
				'user_group_id' => static function(string $attributeName, $attributeValue) {
					if (null !== $groupId = ArrayHelper::getValue(RelUsersGroups::findModel($attributeValue), 'group_id')) {
						return ArrayHelper::getValue(Groups::findModel($groupId), 'name', $attributeValue);
					}
					return $attributeValue;
				}
			]
		];
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
	 * @param int $user
	 * @return bool
	 */
	public static function setRoleInGroup(int $role, int $group, int $user):bool {
		/*Связь пользователя в группе уже есть*/
		/** @var RelUsersGroups|null $rel */
		$rel = RelUsersGroups::find()->where(['group_id' => $group, 'user_id' => $user])->one();
		if ($rel) {
			$relUsersGroupsRoles = new self(['user_group_id' => $rel->id, 'role' => $role]);
			return $relUsersGroupsRoles->save();

		}
		/*Попытка добавления пользователя в группу, в которой он не присутствует. Такое невозможно по логике связей таблиц, но может быть инициировано при сохранении с одновременным удалением */
		return false;
	}

	/**
	 * @return ActiveQuery|RelUsersGroups[]
	 */
	public function getRelUsersGroups():ActiveQuery {
		return $this->hasMany(RelUsersGroups::class, ['id' => 'user_group_id']);
	}

	/**
	 * Возвращает id ролей пользователя в группе (полезно при отображении результата, когда не нужно поддёргивать справочник)
	 * @param int $user
	 * @param int $group
	 * @return int[]
	 */
	public static function getRoleIdInGroup(int $user, int $group):array {
		return ArrayHelper::getColumn(self::find()->joinWith(['relUsersGroups'])->where(['rel_users_groups.user_id' => $user, 'rel_users_groups.group_id' => $group])->select('rel_users_groups_roles.role')->all(), 'role');
	}

}
