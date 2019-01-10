<?php
declare(strict_types = 1);

namespace app\models\groups;

use Yii;
use Yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;

/**
 * UserGroupsSearch represents the model behind the search form about `app\models\UserGroups`.
 * Class GroupsSearch
 * @package app\models\groups
 * @property int|int[] $leaders
 */
class GroupsSearch extends Groups {
	public $leaders;
	public $relGroupTypes_name;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['id'], 'integer'],
			[['name', 'comment', 'type', 'leaders'], 'safe'],
			[['relGroupTypes_name'], 'safe']
		];
	}

	/**
	 * Creates data provider instance with search query applied
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws InvalidArgumentException
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
				'type',
				'leaders',
				'comment',
				'daddy'
			]
		];

		$dataProvider->setSort($MainAttributes);

		$query->joinWith(['relGroupTypes']);

		$query->andFilterWhere(['id' => $this->id])
			->andFilterWhere(['like', 'sys_groups.name', $this->name])
			->andFilterWhere(['=', 'sys_groups.type', $this->type])
			->andFilterWhere(['=', 'sys_groups.daddy', $this->daddy]);
		if (!empty($this->leaders)) {
			$query->joinWith(['relRefUserRoles'])->where(['boss_flag' => true, 'rel_users_groups.user_id' => $this->leaders]);
		}

//		Utils::log($query->createCommand()->rawSql);
		return $dataProvider;
	}
}
