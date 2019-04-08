<?php
declare(strict_types = 1);

namespace app\models\relations;

use app\models\core\ActiveRecordExtended;
use app\modules\groups\models\Groups;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use yii\db\ActiveQuery;

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
class RelUsersGroups extends ActiveRecordExtended {
	use Relations;

	/**
	 * Прототипируем задание связи отношений таблиц в истории
	 * @return array
	 */
	public function historyRelationsOut():array {
		return [
			'Groups' => [
				'model' => Groups::class,//модель, с которой связывает этот релейшен
				'link' => ['id' => 'group_id'],//свойства, по которым происходит связь
				'substitute' => ['group_id' => 'name']//подстановка, т.е. какой атрибут из подстановочной таблицы вернуть вместо входящего атрибута
			],
			'Users' => [
				'model' => Users::class,
				'link' => ['id' => 'user_id'],
				'substitute' => ['user_id' => 'username']
			]

		];
	}

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
