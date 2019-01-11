<?php
declare(strict_types = 1);

namespace app\models\users;

use yii\data\ActiveDataProvider;

/**
 * UsersSearch represents the model behind the search form about `app\models\users\Users`.
 * Class UsersSearch
 * @package app\models\users
 *
 * @property string $groupName Фильтр названия группы
 * @property int[] $roles Фильтр ролей
 * @property int[] $privileges Фильтр привилегий
 */
class UsersSearch extends Users {
	public $groupName;
	public $roles;
	public $privileges;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['id'], 'integer'],
			[['username', 'login', 'email'], 'safe'],
			[['groupName', 'roles', 'privileges'], 'safe']
		];
	}

	/**
	 * @param $params
	 * @param $allowedGroups
	 * @param bool $pagination
	 * @return ActiveDataProvider
	 */
	public function search($params, $allowedGroups, $pagination = true):ActiveDataProvider {
		$query = Users::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id',
				'username',
				'login',
				'email',
				'groupName' => [
					'asc' => ['sys_groups.name' => SORT_ASC],
					'desc' => ['sys_groups.name' => SORT_DESC]
				],
				'roles' => [
					'asc' => ['ref_user_roles.name' => SORT_ASC],
					'desc' => ['ref_user_roles.name' => SORT_DESC]
				],
				'privileges' => [
					'asc' => ['sys_privileges.name' => SORT_ASC],
					'desc' => ['sys_privileges.name' => SORT_DESC]
				]
			]
		]);

		$this->load($params);
		if (false === $pagination) $dataProvider->pagination = $pagination;

		if (!$this->validate()) return $dataProvider;

		$query->joinWith(['relGroups', 'relRefUserRoles', 'relPrivileges']);
		$query->andFilterWhere(['sys_users.id' => $this->id])
			->andFilterWhere(['group_id' => $allowedGroups])
			->andFilterWhere(['like', 'sys_users.username', $this->username])
			->andFilterWhere(['like', 'login', $this->login])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'sys_groups.name', $this->groupName])
			->andFilterWhere(['in', 'ref_user_roles.id', $this->roles])
			->andFilterWhere(['in', 'sys_privileges.id', $this->privileges]);
		return $dataProvider;
	}
}
