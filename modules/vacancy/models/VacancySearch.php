<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models;

use Throwable;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class VacancySearch
 * @property  string $groupName
 * @package app\modules\vacancy\models
 */
class VacancySearch extends Vacancy {
	public $groupName;


	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['vacancy_id', 'ticket_id', 'status', 'group', 'location', 'recruiter', 'employer', 'position', 'role', 'teamlead', 'create_date', 'estimated_close_date', 'groupName'], 'safe']
		];
	}

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
		$query->joinWith(['relGroup']);
		$query->andFilterWhere(['like', 'sys_groups.name', $this->groupName]);
		$query->andFilterWhere(['in', 'status', $this->status]);
		$query->andFilterWhere(['in', 'location', $this->location]);
		$query->andFilterWhere(['in', 'recruiter', $this->recruiter]);
		$query->andFilterWhere(['in', 'position', $this->position]);

		$query->andFilterDateBetween('create_date', $this->create_date);
		$query->andFilterDateBetween('close_date', $this->close_date);
		$query->andFilterDateBetween('estimated_close_date', $this->estimated_close_date);

		Yii::debug($query->createCommand()->rawSql, 'sql');
		return $dataProvider;
	}

}