<?php
declare(strict_types = 1);

namespace app\models\groups;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * UserGroupsSearch represents the model behind the search form about `app\models\UserGroups`.
 * Class GroupsSearch
 * @package app\models\groups
 */
class GroupsSearch extends Groups {

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['id'], 'integer'],
			[['name', 'comment'], 'safe']
		];
	}

	/**
	 * Creates data provider instance with search query applied
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws yii\base\InvalidArgumentException
	 */
	public function search($params):ActiveDataProvider {
		$query = Groups::find()->active();

		$this->load($params);

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$MainAttributes = [
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id',
				'name',
				'comment',
				'daddy'
			]
		];

		$dataProvider->setSort($MainAttributes);

//		$query->joinWith(['users', 'childrens', 'parent', 'supergroups', 'creator']);

//		$query->groupBy('id');


		$query->andFilterWhere(['id' => $this->id])
			->andFilterWhere(['like', 'sys_groups.name', trim($this->name)])
			->andFilterWhere(['=', 'sys_groups.daddy', $this->daddy]);

		return $dataProvider;
	}
}
