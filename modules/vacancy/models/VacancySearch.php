<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models;

use Throwable;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class VacancySearch
 * @package app\modules\vacancy\models
 */
class VacancySearch extends Vacancy {

	/**
	 * @param array $params
	 * @param bool $pagination
	 * @return ActiveDataProvider
	 * @throws Throwable
	 */
	public function search(array $params, $pagination = true):ActiveDataProvider {
		$query = Vacancy::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_DESC]
		]);

		$this->load($params);
		if (false === $pagination) $dataProvider->pagination = $pagination;

		if (!$this->validate()) return $dataProvider;
//
//		$query->joinWith(['userModel']);
//		$query->andFilterWhere(['like', 'sys_users.username', $this->username]);
//		$query->andFilterWhere(['in', 'model', $this->model]);
//		$query->andFilterDateBetween('at', $this->at);

		Yii::debug($query->createCommand()->rawSql, 'sql');
		return $dataProvider;
	}

}