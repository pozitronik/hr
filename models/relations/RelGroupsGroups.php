<?php
declare(strict_types = 1);

namespace app\models\relations;

use app\models\references\refs\RefGroupRelationTypes;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rel_groups_groups".
 *
 * @property int $id
 * @property int $parent_id Вышестоящая группа
 * @property int $child_id Нижестоящая группа
 * @property int $relation Тип связи
 * @property ActiveQuery|RefGroupRelationTypes refGroupsRelationTypes Типы связей (справочник)
 */
class RelGroupsGroups extends ActiveRecord {
	use Relations;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_groups_groups';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['parent_id', 'child_id'], 'required'],
			[['parent_id', 'child_id', 'relation'], 'integer'],
			[['parent_id', 'child_id', 'relation'], 'unique', 'targetAttribute' => ['parent_id', 'child_id', 'relation']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'parent_id' => 'Вышестоящая группа',
			'child_id' => 'Нижестоящая группа',
			'relation' => 'Тип связи'
		];
	}

	/**
	 * @return ActiveQuery|RefGroupRelationTypes
	 */
	public function getRefGroupsRelationTypes() {
		return $this->hasOne(RefGroupRelationTypes::class, ['id' => 'relation']);
	}
}
