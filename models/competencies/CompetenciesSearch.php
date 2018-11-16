<?php
declare(strict_types = 1);

namespace app\models\competencies;
use yii\data\ActiveDataProvider;

/**
 * Class CompetenciesSearch
 * @package app\models\competencies
 */
class CompetenciesSearch extends Competencies {

	/**
	 * @param $params
	 * @return ActiveDataProvider
	 */
	public function search($params):ActiveDataProvider {
		$query = Competencies::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'name'
			]
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere(['name' => $this->name]);

		return $dataProvider;
	}
}
