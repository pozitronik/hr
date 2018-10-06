<?php
declare(strict_types = 1);

namespace app\models\workgroups;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "workgroups".
 *
 * @property int $id
 * @property string $name Название
 * @property string $comment Описание
 * @property int $deleted
 */
class Workgroups extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'workgroups';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['comment'], 'string'],
			[['deleted'], 'integer'],
			[['name'], 'string', 'max' => 512]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'comment' => 'Описание',
			'deleted' => 'Deleted'
		];
	}
}
