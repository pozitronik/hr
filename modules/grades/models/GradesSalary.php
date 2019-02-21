<?php
declare(strict_types = 1);

namespace app\modules\grades\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "grades_salary".
 *
 * @property int $id
 * @property double $min Минимальный оклад
 * @property double $max Максимальный оклад
 * @property int $currency Валюта
 */
class GradesSalary extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'grades_salary';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['min', 'max'], 'number'],
			[['currency'], 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'min' => 'Min',
			'max' => 'Max',
			'currency' => 'Currency'
		];
	}
}
