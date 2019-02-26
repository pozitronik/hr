<?php

namespace app\modules\salary\models;

use Yii;

/**
 * This is the model class for table "salary_fork".
 *
 * @property int $id
 * @property int $position_id Должность
 * @property int $grade_id Грейд
 * @property int $premium_group_id Группа премирования
 * @property int $location_id Локация
 * @property double $min Минимальный оклад
 * @property double $max Максимальный оклад
 * @property int $currency Валюта
 */
class SalaryFork extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'salary_fork';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['position_id', 'grade_id'], 'required'],
			[['position_id', 'grade_id', 'premium_group_id', 'location_id', 'currency'], 'integer'],
			[['min', 'max'], 'number'],
			[['position_id', 'grade_id', 'premium_group_id', 'location_id'], 'unique', 'targetAttribute' => ['position_id', 'grade_id', 'premium_group_id', 'location_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'position_id' => 'Должность',
			'grade_id' => 'Грейд',
			'premium_group_id' => 'Группа премирования',
			'location_id' => 'Локация',
			'min' => 'Минимальный оклад',
			'max' => 'Максимальный оклад',
			'currency' => 'Валюта',
		];
	}
}
