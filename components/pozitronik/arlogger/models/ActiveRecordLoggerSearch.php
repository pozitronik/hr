<?php
declare(strict_types = 1);

namespace app\components\pozitronik\arlogger\models;

use Throwable;
use yii\data\ActiveDataProvider;

/**
 * Class ActiveRecordLoggerSearch
 * @package pozitronik\arlogger\models
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
			[['actions', 'user', 'at', 'model'], 'safe']
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
				'modelKey',
				'model'
			]
		]);

		$this->load($params);
		if (false === $pagination) $dataProvider->pagination = $pagination;

		if (!$this->validate()) return $dataProvider;

		$query->andFilterWhere(['in', 'model', $this->model]);

		return $dataProvider;
	}

}