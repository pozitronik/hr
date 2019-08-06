<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\user_attributes;

use app\models\relations\RelUsersAttributes;
use yii\data\ActiveDataProvider;

/**
 * Поисковая модель атрибутов пользователя
 * Class UserAttributesSearch
 * @package app\modules\dynamic_attributes\models\user_attributes
 */
class UserAttributesSearch extends RelUsersAttributes {
	public $type;

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['id', 'type', 'user_id', 'attribute_id'], 'safe']
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params = []):ActiveDataProvider {
		$query = RelUsersAttributes::getUserAttributesScope($this->user_id);

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['type' => SORT_ASC],
			'attributes' => [
				'id',
				'user_id',
				'attribute_id',
				'type' => [
					'asc' => ['ISNULL(rel_users_attributes_types.type), rel_users_attributes_types.type' => SORT_ASC],
					'desc' => ['ISNULL(rel_users_attributes_types.type), rel_users_attributes_types.type' => SORT_DESC]
				], 1
			]
		]);

		$this->load($params);

		if (!$this->validate()) return $dataProvider;
		$query->andFilterWhere(['rel_users_attributes_types.type' => $this->type]);

		return $dataProvider;
	}
}