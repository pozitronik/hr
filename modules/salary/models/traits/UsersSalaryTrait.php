<?php
declare(strict_types = 1);

namespace app\modules\salary\models\traits;

use app\modules\salary\models\references\RefGrades;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefSalaryPremiumGroups;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\salary\models\relations\RelUsersSalary;
use app\modules\salary\models\SalaryFork;
use yii\db\ActiveQuery;

/**
 * Trait UsersSalaryTrait
 * Трейт для подключения функций модуля зарплат к пользователям
 * @package app\modules\salary\models\traits
 *
 * @property RefUserPositions|ActiveQuery $relRefUserPositions
 * @property RelUsersSalary|ActiveQuery $relUsersSalary
 * @property RefGrades|ActiveQuery $relGrade
 * @property RefSalaryPremiumGroups|ActiveQuery|null $relPremiumGroup
 * @property RefLocations|ActiveQuery|null $relLocation
 *
 * @property-read null|SalaryFork $relSalaryFork
 *
 */
trait UsersSalaryTrait {

	/**
	 * @return ActiveQuery
	 */
	public function getRelUsersSalary():ActiveQuery {
		if (null === RelUsersSalary::find()->where(['user_id' => $this->id])->one()) (new RelUsersSalary(['user_id' => $this->id]))->save();
		return $this->hasOne(RelUsersSalary::class, ['user_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelGrade():ActiveQuery {
		return $this->hasOne(RefGrades::class, ['id' => 'grade_id'])->via('relUsersSalary');
	}

	/**
	 * @param mixed $relGrade
	 */
	public function setRelGrade($relGrade):void {
		$this->relUsersSalary->setAndSaveAttribute('grade_id', $relGrade);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelPremiumGroup():ActiveQuery {
		return $this->hasOne(RefSalaryPremiumGroups::class, ['id' => 'premium_group_id'])->via('relUsersSalary');
	}

	/**
	 * @param mixed $relPremiumGroup
	 */
	public function setRelPremiumGroup($relPremiumGroup):void {
		$this->relUsersSalary->setAndSaveAttribute('premium_group_id', $relPremiumGroup);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelLocation():ActiveQuery {
		return $this->hasOne(RefLocations::class, ['id' => 'location_id'])->via('relUsersSalary');
	}

	/**
	 * @param mixed $relLocation
	 */
	public function setRelLocation($relLocation):void {
		$this->relUsersSalary->setAndSaveAttribute('location_id', $relLocation);
	}

	/**
	 * @return null|SalaryFork
	 */
	public function getRelSalaryFork():?SalaryFork {
		return SalaryFork::find()
			->where(['position_id' => $this->position])
			->andWhere(['grade_id' => $this->relUsersSalary->grade_id, 'premium_group_id' => $this->relUsersSalary->premium_group_id, 'location_id' => $this->relUsersSalary->location_id])
			->one();
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelRefUserPositions():ActiveQuery {
		return $this->hasOne(RefUserPositions::class, ['id' => 'position']);
	}

}