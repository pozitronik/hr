<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline;

use yii\data\ActiveDataProvider;

/**
 * Class ImportBeelineDecomposedSearch
 */
class ImportBeelineDecomposedSearch extends ImportBeelineDecomposed {

	/**
	 * @param array $params
	 * @param int|null $domain
	 * @return ActiveDataProvider
	 */
	public function search(array $params, ?int $domain = null):ActiveDataProvider {
		$query = self::find();
		if (null !== $domain) $query->andWhere(['domain' => $domain]);
		$this->load($params);

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$MainAttributes = [
			'defaultOrder' => ['id' => SORT_ASC]
		];

		$dataProvider->setSort($MainAttributes);

		return $dataProvider;
	}
}