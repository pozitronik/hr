<?php
declare(strict_types = 1);

namespace app\models\users;

use yii\data\ActiveDataProvider;

/**
 * UsersSearch represents the model behind the search form about `app\models\users\Users`.
 * Class UsersSearch
 * @package app\models\users
 */
class UsersSearch extends Users {
	public $mainGroupName;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['id'], 'integer'],
			[['username', 'login', 'email'], 'safe']
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
				'email'
			]
		]);

		$this->load($params);
		if (false === $pagination) $dataProvider->pagination = $pagination;

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'sys_users.id' => $this->id
		])->andFilterWhere(['group_id' => $allowedGroups])
			->andFilterWhere(['like', 'sys_users.username', $this->username])
			->andFilterWhere(['like', 'login', $this->login])
			->andFilterWhere(['like', 'email', $this->email]);

		return $dataProvider;
	}
}
