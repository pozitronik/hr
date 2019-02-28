<?php
declare(strict_types = 1);

namespace app\modules\salary\models;

use app\helpers\Utils;
use InvalidArgumentException;
use yii\data\ActiveDataProvider;

/**
 * Class SalaryForkSearch
 * @package app\modules\salary\models
 */
class SalaryForkSearch extends SalaryFork {
	public $refUserPositionName;
	public $refGradeName;
	public $refPremiumGroupName;
	public $refLocationName;
	public $mid;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['id', 'position_id', 'min', 'max', 'mid'], 'integer'],
			[['refUserPositionName', 'refGradeName', 'refPremiumGroupName', 'refLocationName'], 'string']
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
			'defaultOrder' => ['refUserPositionName' => SORT_ASC],
			'attributes' => [
				'refUserPositionName' => [
					'asc' => ['ref_user_positions.name' => SORT_ASC],
					'desc' => ['ref_user_positions.name' => SORT_DESC]
				],
				'refGradeName' => [
					'asc' => ['ref_salary_grades.name' => SORT_ASC],
					'desc' => ['ref_salary_grades.name' => SORT_DESC]
				],
				'refPremiumGroupName' => [
					'asc' => ['ref_salary_premium_group.name' => SORT_ASC],
					'desc' => ['ref_salary_premium_group.name' => SORT_DESC]
				],
				'refLocationName' => [
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
		$query->andFilterWhere(['like', 'ref_user_positions.name', Utils::MakeLike($this->refUserPositionName), false]);
		$query->andFilterWhere(['like', 'ref_salary_grades.name', Utils::MakeLike($this->refGradeName), false]);
		$query->andFilterWhere(['like', 'ref_salary_premium_group.name', Utils::MakeLike($this->refPremiumGroupName), false]);
		$query->andFilterWhere(['like', 'ref_locations.name', Utils::MakeLike($this->refLocationName), false]);

		return $dataProvider;
	}

}
