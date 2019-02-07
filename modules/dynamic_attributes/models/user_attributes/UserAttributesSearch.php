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
			[['id', 'type', 'user_id', 'attribute_id'], 'integer'],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'attribute_id' => 'Attribute ID',
			'type' => 'Тип'
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params):ActiveDataProvider {
		$query = RelUsersAttributes::getUserAttributesScope($this->user_id);

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id',
				'user_id',
				'attribute_id',
				'type'
			]
		]);

		$this->load($params);

		if (!$this->validate()) return $dataProvider;
//
//
//		$query->distinct();
//
//		$query->andFilterWhere(['sys_users.id' => $this->id])
//			->andFilterWhere(['group_id' => $allowedGroups])
//			->andFilterWhere(['like', 'sys_users.username', $this->username])
//			->andFilterWhere(['like', 'login', $this->login])
//			->andFilterWhere(['like', 'email', $this->email])
//			->andFilterWhere(['like', 'sys_groups.name', $this->groupName])
//			->andFilterWhere(['in', 'ref_user_roles.id', $this->roles])
//			->andFilterWhere(['in', 'sys_privileges.id', $this->privileges]);
		return $dataProvider;
	}
}