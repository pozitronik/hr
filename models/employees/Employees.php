<?php
declare(strict_types = 1);

namespace app\models\employees;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "employees".
 *
 * @property int $id
 * @property string $name ФИО
 * @property int $deleted
 */
class Employees extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'employees';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
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
			'name' => 'ФИО',
			'deleted' => 'Deleted'
		];
	}
}
