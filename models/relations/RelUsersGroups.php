<?php
declare(strict_types = 1);

namespace app\models\relations;

use app\models\core\ActiveRecordExtended;
use app\modules\groups\models\Groups;
use app\modules\history\models\HistoryEventInterface;
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
 *
 */
class RelUsersGroups extends ActiveRecordExtended {
	use Relations;

	/**
	 * {@inheritDoc}
	 */
	public function historyRules():array {
		return [
			'eventConfig' => [
				'eventLabels' => static function(int $eventType, string $default):string {//for example
					switch ($eventType) {
						case HistoryEventInterface::EVENT_CREATED:
							return 'Добавление в группу';
						case HistoryEventInterface::EVENT_DELETED:
							return 'Удаление из группы';
					}
					return $default;
				}
			],
			'attributes' => [
				'group_id' => [Groups::class => 'name'],
				'user_id' => [Users::class => 'username']
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
