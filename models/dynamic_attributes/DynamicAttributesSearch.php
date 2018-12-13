<?php
declare(strict_types = 1);

namespace app\models\dynamic_attributes;

use yii\data\ActiveDataProvider;

/**
 * Class DynamicAttributesSearch
 * @package app\models\dynamic_attributes
 */
class DynamicAttributesSearch extends DynamicAttributes {
	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['id', 'name'], 'safe']
		];
	}

	/**
	 * @param $params
	 * @return ActiveDataProvider
	 */
	public function search($params):ActiveDataProvider {
		$query = DynamicAttributes::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id',
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
