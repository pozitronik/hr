<?php

namespace app\models\workgroups;

use Yii;

/**
 * This is the model class for table "workgroups".
 *
 * @property int $id
 * @property string $name Название
 * @property string $comment Описание
 * @property int $deleted
 */
class Workgroups extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'workgroups';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['comment'], 'string'],
			[['deleted'], 'integer'],
			[['name'], 'string', 'max' => 512],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'comment' => 'Описание',
			'deleted' => 'Deleted',
		];
	}
}
