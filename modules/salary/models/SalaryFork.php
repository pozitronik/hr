<?php

namespace app\modules\salary\models;

use app\modules\salary\models\references\RefGrades;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefSalaryPremiumGroups;
use app\modules\salary\models\references\RefUserPositions;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
 *
 * @property RefUserPositions|ActiveQuery $refUserPosition
 * @property RefGrades|ActiveQuery $refGrade
 * @property RefSalaryPremiumGroups|ActiveQuery|null $refPremiumGroup
 * @property RefLocations|ActiveQuery|null $refLocation
 */
class SalaryFork extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'salary_fork';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['position_id', 'grade_id'], 'required'],
			[['position_id', 'grade_id', 'premium_group_id', 'location_id', 'currency'], 'integer'],
			[['min', 'max'], 'number'],
			[['position_id', 'grade_id', 'premium_group_id', 'location_id'], 'unique', 'targetAttribute' => ['position_id', 'grade_id', 'premium_group_id', 'location_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
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

	/**
	 * @return RefUserPositions|ActiveQuery
	 */
	public function getRefUserPosition() {
		return $this->hasOne(RefUserPositions::class, ['id' => 'position_id']);
	}

	/**
	 * @return RefGrades|ActiveQuery
	 */
	public function getRefGrade() {
		return $this->hasOne(RefGrades::class, ['id' => 'grade_id']);
	}

	/**
	 * @return RefSalaryPremiumGroups|ActiveQuery|null
	 */
	public function getRefPremiumGroup() {
		return $this->hasOne(RefSalaryPremiumGroups::class, ['id' => 'premium_group_id']);
	}

	/**
	 * @return RefLocations|ActiveQuery|null
	 */
	public function getRefLocation() {
		return $this->hasOne(RefLocations::class, ['id' => 'location_id']);
	}
}
