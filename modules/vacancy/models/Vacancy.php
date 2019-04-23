<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models;

use app\helpers\ArrayHelper;
use app\helpers\Date;
use app\models\core\ActiveRecordExtended;
use app\models\core\StrictInterface;
use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\salary\models\references\RefGrades;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefSalaryPremiumGroups;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\models\Users;
use app\modules\vacancy\models\references\RefVacancyRecruiters;
use app\modules\vacancy\models\references\RefVacancyStatuses;
use app\widgets\alert\AlertModel;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * This is the model class for table "sys_vacancy".
 *
 * @property int $id
 * @property int|null $vacancy_id Внешний ID вакансии
 * @property int|null $ticket_id ID заявки на подбор
 * @property int|null $status Статус вакансии
 * @property int|null $group Группа
 * @property string|null $name Опциональное название вакансии
 * @property int|null $location Локация
 * @property int|null $recruiter Рекрутер
 * @property int|null $employer Нанимающий руководитель
 * @property int|null $position Должность
 *
 * @property int|null $premium_group Премиальная группа
 * @property int|null $grade Грейд
 *
 * @property int|null $role Назначение/роль
 * @property int|null $teamlead teamlead
 * @property string $create_date Дата заведения вакансии
 * @property string|null $close_date Дата закрытия вакансии
 * @property string|null $estimated_close_date Дата ожидаемого закрытия вакансии
 * @property int $daddy Автор вакансии
 * @property bool $deleted
 *
 * @property Groups|ActiveQuery $relGroup
 * @property RefUserPositions $relRefUserPosition
 * @property RefLocations $relRefLocation
 * @property RefVacancyStatuses $relRefVacancyStatus
 * @property RefVacancyRecruiters $relRefVacancyRecruiter
 * @property RefSalaryPremiumGroups $relRefSalaryPremiumGroup
 * @property RefGrades $relRefGrade
 * @property Users $relEmployer
 * @property Users $relTeamlead
 */
class Vacancy extends ActiveRecordExtended implements StrictInterface {

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_vacancy';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['vacancy_id', 'ticket_id', 'status', 'group', 'location', 'recruiter', 'employer', 'position', 'role', 'teamlead', 'daddy', 'premium_group', 'grade'], 'integer'],
			[['group', 'position', 'create_date', 'daddy'], 'required'],
			[['create_date', 'close_date', 'estimated_close_date'], 'safe'],
			[['name'], 'string', 'max' => 255],
			[['vacancy_id', 'ticket_id'], 'unique']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'vacancy_id' => 'Внешний ID вакансии',
			'ticket_id' => 'ID заявки на подбор',
			'status' => 'Статус вакансии',
			'group' => 'Группа',
			'groupName' => 'Группа',
			'name' => 'Опциональное название вакансии',
			'location' => 'Локация',
			'recruiter' => 'Рекрутер',
			'employer' => 'Нанимающий руководитель',
			'employerName' => 'Нанимающий руководитель',
			'position' => 'Должность',
			'premium_group' => 'Группа премирования',
			'grade' => 'Грейд',
			'role' => 'Назначение/роль',
			'teamlead' => 'Тимлид',
			'teamleadName' => 'Тимлид',
			'create_date' => 'Дата заведения вакансии',
			'close_date' => 'Дата закрытия вакансии',
			'estimated_close_date' => 'Дата ожидаемого закрытия вакансии',
			'daddy' => 'Автор вакансии'
		];
	}

	/**
	 * @param array|null $paramsArray
	 * @return bool
	 * @throws Exception
	 */
	public function createModel(?array $paramsArray):bool {
		$transaction = self::getDb()->beginTransaction();
		if ($this->loadArray($paramsArray)) {
			$this->updateAttributes([
				'daddy' => CurrentUser::Id(),
				'create_date' => Date::lcDate()
			]);
			if ($this->save()) {/*Возьмём разницу атрибутов и массива параметров - в нем будут новые атрибуты, которые теперь можно заполнить*/
				$this->loadArray(ArrayHelper::diff_keys($this->attributes, $paramsArray));
				/** @noinspection NotOptimalIfConditionsInspection */
				if ($this->save()) {
					$transaction->commit();
					$this->refresh();
					AlertModel::SuccessNotify();
					return true;
				}
				AlertModel::ErrorsNotify($this->errors);
			}
			AlertModel::ErrorsNotify($this->errors);//todo: разобраться уже с алертами, м.б. переделать в дефолтное поведение
		}
		$transaction->rollBack();
		return false;
	}

	/**
	 * @param array|null $paramsArray
	 * @return bool
	 */
	public function updateModel(?array $paramsArray):bool {
		if ($this->loadArray($paramsArray)) {
			if ($this->save()) {
				AlertModel::SuccessNotify();
				$this->refresh();
				return true;
			}
			AlertModel::ErrorsNotify($this->errors);
		}
		return false;
	}

	/**
	 * @return Groups|ActiveQuery
	 */
	public function getRelGroup() {
		return $this->hasOne(Groups::class, ['id' => 'group']);
	}

	/**
	 * @return RefUserPositions|ActiveQuery
	 */
	public function getRelRefUserPosition() {
		return $this->hasOne(RefUserPositions::class, ['id' => 'position']);
	}

	/**
	 * @return RefLocations|ActiveQuery
	 */
	public function getRelRefLocation() {
		return $this->hasOne(RefLocations::class, ['id' => 'location']);
	}

	/**
	 * @return RefVacancyStatuses|ActiveQuery
	 */
	public function getRelRefVacancyStatus() {
		return $this->hasOne(RefVacancyStatuses::class, ['id' => 'status']);
	}

	/**
	 * @return RefVacancyRecruiters|ActiveQuery
	 */
	public function getRelRefVacancyRecruiter() {
		return $this->hasOne(RefVacancyRecruiters::class, ['id' => 'recruiter']);
	}

	/**
	 * @return RefSalaryPremiumGroups|ActiveQuery
	 */
	public function getRelRefSalaryPremiumGroup() {
		return $this->hasOne(RefSalaryPremiumGroups::class, ['id' => 'premium_group']);
	}

	/**
	 * @return RefGrades|ActiveQuery
	 */
	public function getRelRefGrade() {
		return $this->hasOne(RefGrades::class, ['id' => 'grade']);
	}

	/**
	 * @return Users|ActiveQuery
	 */
	public function getRelEmployer() {
		return $this->hasOne(Users::class, ['id' => 'employer']);
	}

	/**
	 * @return Users|ActiveQuery
	 */
	public function getRelTeamlead() {
		return $this->hasOne(Users::class, ['id' => 'teamlead']);
	}
}
