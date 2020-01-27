<?php
declare(strict_types = 1);

namespace app\modules\targets\models;

use yii\data\ActiveDataProvider;

/**
 * Class TargetsSearch
 * @package app\modules\targets\models
 */
class TargetsSearch extends Targets {
	public $parent_name;
	public $group_name;
	public $user_name;

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['id'], 'integer'],
			[['name', 'comment', 'type', 'result_type', 'parent_name', 'group_name', 'user_name'], 'safe']
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params):ActiveDataProvider {
		$query = Targets::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id',
				'name',
				'type',
				'result_type',
				'parent_name' => [
					'asc' => ['parentTarget.name' => SORT_ASC],
					'desc' => ['parentTarget.name' => SORT_DESC]
				],
				'group_name' => [
					'asc' => ['sys_groups.name' => SORT_ASC],
					'desc' => ['sys_groups.name' => SORT_DESC]
				],
				'user_name' => [
					'asc' => ['sys_users.name' => SORT_ASC],
					'desc' => ['sys_users.name' => SORT_DESC]
				]
			]
		]);

		$this->load($params);

		if (!$this->validate()) return $dataProvider;

		$query->joinWith(['relGroups', 'relUsers', 'relParentTarget as parentTarget', 'relTargetsTypes', 'relTargetsResults']);

//		$query->distinct();

		$query->andFilterWhere(['sys_users.id' => $this->id])
			->andFilterWhere(['like', 'parentTarget.name', $this->parent_name])
			->andFilterWhere(['like', 'sys_users.username', $this->user_name])
			->andFilterWhere(['like', 'sys_groups.name', $this->group_name])
//			->andFilterWhere(['in', 'IFNULL(`rel_user_position_types`.`position_type_id`, `rel_ref_user_positions_types`.`position_type_id`)', $this->positionType])//таким образом решаем проблему фильтрации по типу должности, не вводя промежуточную вью.
			->andFilterWhere(['in', 'ref_targets_types.id', $this->type])
			->andFilterWhere(['in', 'ref_targets_results.id', $this->result_type]);

//		Yii::debug($query->createCommand()->rawSql, 'sql');
		return $dataProvider;
	}
}