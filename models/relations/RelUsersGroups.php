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

	/**
	 * Добавляет группу к пользователю. Или пользователя в группу. Как посмотреть.
	 * @param integer|array $user_id
	 * @param integer|array $group_id
	 */
	private static function linkUserGroup($user_id, $group_id):void {
		if (self::find()->where(compact('user_id', 'group_id'))->one()) return;//duplicate entry
		$link = new self();
		$link->user_id = $user_id;
		$link->group_id = $group_id;
		$link->save();
	}

	/**
	 * Добавляет группы к пользователям. Или пользователей в группы. Как посмотреть.
	 * Жрёт массивы и отдельные идентификаторы
	 * @param integer|array $user
	 * @param integer|array $group
	 */
	public static function linkUsersGroups($user, $group):void {
		if (is_array($user)) {
			foreach ($user as $user_id) {
				if (is_array($group)) {
					foreach ($group as $group_id) {
						self::linkUserGroup($user_id, $group_id);
					}
				} else self::linkUserGroup($user_id, $group);

			}
		} else if (is_array($group)) {
			foreach ($group as $group_id) {
				self::linkUserGroup($user, $group_id);
			}
		} else self::linkUserGroup($user, $group);
	}
}
