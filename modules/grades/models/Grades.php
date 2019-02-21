<?php
declare(strict_types = 1);

namespace app\modules\grades\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "grades".
 *
 * @property int $id
 * @property string $name
 */
class Grades extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'grades';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'string', 'max' => 255]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Name'
		];
	}
}
