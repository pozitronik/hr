<?php
declare(strict_types = 1);

namespace app\modules\targets\models;


use yii\data\ActiveDataProvider;

/**
 * Class TargetsSearch
 * @package app\modules\targets\models
 */
class TargetsSearch extends Targets {
	public $group_name;
	public $user_name;

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['id'], 'integer'],
			[['name', 'comment', 'type', 'result_type', 'group_name', 'user_name'], 'safe'],
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

			]
		]);

		$this->load($params);

		if (!$this->validate()) return $dataProvider;

//		$query->joinWith(['relGroups', 'relRefUserPositions', 'relRefUserPositionsTypesOwn as x', 'relRefUserRoles', 'relPrivileges', 'refUserPositionTypes']);

//		$query->distinct();

//		$query->andFilterWhere(['sys_users.id' => $this->id])
//			->andFilterWhere(['group_id' => $allowedGroups])
//			->andFilterWhere(['like', 'sys_users.username', $this->username])
//			->andFilterWhere(['like', 'login', $this->login])
//			->andFilterWhere(['like', 'email', $this->email])
//			->andFilterWhere(['like', 'sys_groups.name', $this->groupName])
//			->andFilterWhere(['in', 'ref_user_positions.id', $this->positions])
//			->andFilterWhere(['in', 'ref_user_roles.id', $this->roles])
//			->andFilterWhere(['in', 'sys_privileges.id', $this->privileges])
//			->andFilterWhere(['in', 'IFNULL(`rel_user_position_types`.`position_type_id`, `rel_ref_user_positions_types`.`position_type_id`)', $this->positionType])//таким образом решаем проблему фильтрации по типу должности, не вводя промежуточную вью.
//			->andFilterWhere(['in', 'sys_groups.id', $this->groupId]);

//		Yii::debug($query->createCommand()->rawSql, 'sql');
		return $dataProvider;
	}
}