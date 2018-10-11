<?php

namespace app\models\relations;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rel_groups_groups".
 *
 * @property int $id
 * @property int $parent_id Вышестоящая группа
 * @property int $child_id Нижестоящая группа
 * @property int $relation Тип связи
 */
class RelGroupsGroups extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'rel_groups_groups';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['parent_id', 'child_id'], 'required'],
			[['parent_id', 'child_id', 'relation'], 'integer'],
			[['parent_id', 'child_id', 'relation'], 'unique', 'targetAttribute' => ['parent_id', 'child_id', 'relation']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'parent_id' => 'Вышестоящая группа',
			'child_id' => 'Нижестоящая группа',
			'relation' => 'Тип связи',
		];
	}


}
