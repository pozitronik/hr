<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use Throwable;
use yii\data\ActiveDataProvider;

/**
 * Class ActiveRecordLoggerSearch
 * @package app\modules\history\models
 *
 */
class ActiveRecordLoggerSearch extends ActiveRecordLogger {
	public $actions;
	public $username;

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['actions', 'username', 'at', 'model'], 'safe']
		];
	}

	/**
	 * @param array $params
	 * @param bool $pagination
	 * @return ActiveDataProvider
	 * @throws Throwable
	 */
	public function search(array $params, $pagination = true):ActiveDataProvider {
		$query = ActiveRecordLogger::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_DESC],
			'attributes' => [
				'id',
				'at',
				'userModel',
				'modelKey',
				'model'
			]
		]);

		$this->load($params);
		if (false === $pagination) $dataProvider->pagination = $pagination;

		if (!$this->validate()) return $dataProvider;

//		$query->distinct();
		$query->joinWith(['userModel']);
		$query->andFilterWhere(['like', 'sys_users.username', $this->username]);
		$query->andFilterWhere(['in', 'model', $this->model]);
		$query->andFilterDateBetween('at', $this->at);

//		Yii::debug($query->createCommand()->rawSql, 'sql');
		return $dataProvider;
	}

}