<?php
declare(strict_types = 1);

namespace app\modules\salary\models;

use InvalidArgumentException;
use yii\data\ActiveDataProvider;

/**
 * Class SalaryForkSearch
 * @package app\modules\salary\models
 */
class SalaryForkSearch extends SalaryFork {
	public $positionId;
	public $gradeId;
	public $premiumGroupId;
	public $locationId;
	public $mid;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['id', 'min', 'max', 'mid'], 'integer'],
//			[['refUserPositionName', 'refGradeName', 'refPremiumGroupName', 'refLocationName'], 'string'],//если нужен будет строковой поиск
			[['positionId', 'gradeId', 'premiumGroupId', 'locationId'], 'safe']
		];
	}

	/**
	 * Creates data provider instance with search query applied
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws InvalidArgumentException
	 */
	public function search(array $params):ActiveDataProvider {
		$query = SalaryFork::find();

		$this->load($params);

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$MainAttributes = [
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id',
				'positionId' => [
					'asc' => ['ref_user_positions.name' => SORT_ASC],
					'desc' => ['ref_user_positions.name' => SORT_DESC]
				],
				'gradeId' => [
					'asc' => ['ref_salary_grades.name' => SORT_ASC],
					'desc' => ['ref_salary_grades.name' => SORT_DESC]
				],
				'premiumGroupId' => [
					'asc' => ['ref_salary_premium_group.name' => SORT_ASC],
					'desc' => ['ref_salary_premium_group.name' => SORT_DESC]
				],
				'locationId' => [
					'asc' => ['ref_locations.name' => SORT_ASC],
					'desc' => ['ref_locations.name' => SORT_DESC]
				],
				'min',
				'max'
			]
		];

		$dataProvider->setSort($MainAttributes);

		$query->joinWith(['refUserPosition', 'refGrade', 'refPremiumGroup', 'refLocation']);

		$query->andFilterWhere(['salary_fork.id' => $this->id]);
		$query->andFilterWhere(['in', 'salary_fork.position_id', $this->positionId]);
		$query->andFilterWhere(['in', 'salary_fork.grade_id', $this->gradeId]);
		$query->andFilterWhere(['in', 'salary_fork.premium_group_id', $this->premiumGroupId]);
		$query->andFilterWhere(['in', 'salary_fork.location_id', $this->locationId]);

		return $dataProvider;
	}

}
