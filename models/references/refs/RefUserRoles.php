<?php
declare(strict_types = 1);

namespace app\models\references\refs;

use app\models\groups\Groups;
use app\models\references\Reference;
use app\models\relations\RelUsersGroups;
use app\models\relations\RelUsersGroupsRoles;
use app\models\users\Users;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "ref_user_roles".
 *
 * @property int $id
 * @property string $name Название
 * @property int $deleted
 *
 * custom properties:
 * @property bool $boss_flag
 * @property string $color
 *
 *
 * @property ActiveQuery|RelUsersGroupsRoles[] $relUsersGroupsRoles Связующий релейшен к привязкам пользователей в группы (just via)
 * @property ActiveQuery|RelUsersGroups[] $relUsersGroups Релейшен к привязке пользователей в группах
 * @property ActiveQuery|Groups[] $groups
 * @property ActiveQuery|Users[] $users
 *
 */
class RefUserRoles extends Reference {
	public $menuCaption = 'Роли пользователей внутри групп';
	public $menuIcon = false;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_user_roles';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'unique'],
			[['deleted'], 'integer'],
			[['name'], 'string', 'max' => 256],
			[['boss_flag'], 'boolean'],
			[['color'], 'safe']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'deleted' => 'Deleted',
			'boss_flag' => 'Лидер',
			'color' => 'Цвет'
		];
	}

	/**
	 * @return RelUsersGroupsRoles[]|ActiveQuery
	 */
	public function getRelUsersGroupsRoles() {
		return $this->hasMany(RelUsersGroupsRoles::class, ['role' => 'id']);
	}

	/**
	 * @return RelUsersGroups[]|ActiveQuery
	 */
	public function getRelUsersGroups() {
		return $this->hasMany(RelUsersGroups::class, ['id' => 'user_group_id'])->via('relUsersGroupsRoles');
	}

	/**
	 * @return Groups[]|ActiveQuery
	 */
	public function getGroups() {
		return $this->hasMany(Groups::class, ['id' => 'group_id'])->via('relUsersGroups');
	}

	/**
	 * @return Users[]|ActiveQuery
	 */
	public function getUsers() {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relUsersGroups');
	}

	/**
	 * Возвращает набор ролей для пользователя $user в группе $group
	 * @param int $userId
	 * @param int $groupId
	 * @return self[] array
	 */

	public static function getUserRolesInGroup(int $userId, int $groupId):array {
		return self::find()->joinWith('relUsersGroups')->where(['user_id' => $userId, 'group_id' => $groupId])->all();
	}

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 */
	public static function merge(int $fromId, int $toId):void {
		RelUsersGroupsRoles::updateAll(['role' => $toId], ['role' => $fromId]);
		self::deleteAll(['id' => $fromId]);
		self::flushCache();
	}

}
