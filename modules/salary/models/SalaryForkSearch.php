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
class SalaryForkSearch extends SalaryFork{
	public $refUserPositionName;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['id', 'position_id'], 'integer'],
			[['refUserPositionName'], 'string']
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
			]
		];

		$dataProvider->setSort($MainAttributes);

		$query->joinWith(['refUserPosition', 'refGrade', 'refPremiumGroup', 'refLocation']);

		$query->andFilterWhere(['salary_fork.id' => $this->id]);
		$query->andFilterWhere(['like', 'ref_user_positions.name', Utils::MakeLike($this->refUserPositionName), false]);


		return $dataProvider;
	}
}
