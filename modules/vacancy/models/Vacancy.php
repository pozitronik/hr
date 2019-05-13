<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models;

use app\helpers\Utils;
use app\models\relations\RelUsersGroupsRoles;
use app\widgets\alert\AlertModel;
use pozitronik\helpers\ArrayHelper;
use app\helpers\DateHelper;
use app\models\core\ActiveRecordExtended;
use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\history\models\HistoryEventInterface;
use app\modules\salary\models\references\RefGrades;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefSalaryPremiumGroups;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\modules\vacancy\models\references\RefVacancyRecruiters;
use app\modules\vacancy\models\references\RefVacancyStatuses;
use app\modules\vacancy\models\relations\RelVacancyGroupRoles;
use Throwable;
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
 *
 * @property RelVacancyGroupRoles[]|ActiveQuery $relVacancyGroupRoles Релейшен к таблице связей с ролями
 * @property ActiveQuery|RefUserRoles[] $relRefUserRoles Релейшен к справочнику ролей пользователей
 */
class Vacancy extends ActiveRecordExtended {
	public $isOpen = true;//todo: статус готовности подбора
	public $username = 'Пупкин-заде Йован Пафнутьич';//todo: статус готовности подбора

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
			[['vacancy_id', 'ticket_id', 'status', 'group', 'location', 'recruiter', 'employer', 'position', 'teamlead', 'daddy', 'premium_group', 'grade'], 'integer'],
			[['group', 'position', 'create_date', 'daddy'], 'required'],
			[['create_date', 'close_date', 'estimated_close_date'], 'safe'],
			[['name'], 'string', 'max' => 255],
			[['vacancy_id', 'ticket_id'], 'unique'],
			[['relRefUserRoles'], 'safe'],
			[['daddy'], 'default', 'value' => CurrentUser::Id()],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()]
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
			'relRefUserRoles' => 'Назначение/роль',
			'relVacancyGroupRoles' => 'Назначение/роль',
			'teamlead' => 'Тимлид',
			'teamleadName' => 'Тимлид',
			'create_date' => 'Дата заведения вакансии',
			'close_date' => 'Дата закрытия вакансии',
			'estimated_close_date' => 'Дата ожидаемого закрытия вакансии',
			'daddy' => 'Автор вакансии',
			'username' => 'ФИО финалиста'
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function historyRules():array {
		return [
			'attributes' => [
				'daddy' => [Users::class => 'username'],
				'position' => [RefUserPositions::class => 'name'],
				'group' => [Groups::class => 'name'],
				'status' => [RefVacancyStatuses::class => 'name'],
				'location' => [RefLocations::class => 'name'],
				'recruiter' => [RefVacancyRecruiters::class => 'name'],
				'premium_group' => [RefSalaryPremiumGroups::class => 'name'],
				'employer' => [Users::class => 'username'],
				'teamlead' => [Users::class => 'username'],
				'deleted' => false
			],
			'relations' => [
				RelVacancyGroupRoles::class => ['id' => 'vacancy_id']
			],
			'events' => [
				HistoryEventInterface::EVENT_DELETED => [
					'deleted' => [
						'from' => false,
						'to' => true
					]
				]
			]
		];
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

	/**
	 * @return RelVacancyGroupRoles[]|ActiveQuery
	 */
	public function getRelVacancyGroupRoles() {
		return $this->hasMany(RelVacancyGroupRoles::class, ['vacancy_id' => 'id']);
	}

	/**
	 * @param int[] $roles
	 * @throws Throwable
	 */
	public function setRelRefUserRoles($roles):void {
		$droppedRoles = array_diff(ArrayHelper::getColumn($this->relVacancyGroupRoles, 'role_id'), (array)$roles);
		RelVacancyGroupRoles::unlinkModels($this, $droppedRoles);
		RelVacancyGroupRoles::linkModels($this, $roles);
	}

	/**
	 * @return RefUserRoles[]|ActiveQuery
	 */
	public function getRelRefUserRoles() {
		return $this->hasMany(RefUserRoles::class, ['id' => 'role_id'])->via('relVacancyGroupRoles');
	}

	/**
	 * Прототипирую функцию создания пользователя из вакансии
	 * @return null|int New user id on success
	 * @throws Exception
	 */
	public function toUser():?int {
		$transaction = static::getDb()->beginTransaction();
		$user = new Users([
			'username' => $this->username,
			'login' => Utils::generateLogin(),
			'password' => Utils::gen_uuid(5),
			'email' => Utils::generateLogin()."@localhost",
			'position' => $this->position
		]);
		if (true === $saved = $user->save()) {
			$this->refresh();//переподгрузим атрибуты
			$user->relGroups = $this->group;
			foreach ((array)$this->relRefUserRoles as $role) {
				RelUsersGroupsRoles::setRoleInGroup($role->id, $this->group, $user->id);

			}

			$user->relGrade = $this->grade;
			$user->relPremiumGroup = $this->premium_group;
			$user->relLocation = $this->location;
		}
		if (true === $saved = $this->save()) {
			$transaction->commit();
			AlertModel::SuccessNotify();
			return $user->id;
		}
		AlertModel::ErrorsNotify($this->errors);
		$transaction->rollBack();
		return null;

	}
}
