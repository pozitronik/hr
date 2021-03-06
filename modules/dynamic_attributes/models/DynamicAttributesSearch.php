<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models;

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
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params = []):ActiveDataProvider {
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
