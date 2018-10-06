<?php

namespace app\models\employees;

use Yii;

/**
 * This is the model class for table "employees".
 *
 * @property int $id
 * @property string $name ФИО
 * @property int $deleted
 */
class Employees extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'employees';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
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
			'name' => 'ФИО',
			'deleted' => 'Deleted',
		];
	}
}
