<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models\references;

use app\modules\groups\models\Groups;
use pozitronik\references\models\CustomisableReference;
use app\modules\vacancy\models\Vacancy;
use yii\db\ActiveQuery;

/**
 * Class RefVacancyStatuses
 * @package app\modules\salary\models\vacancy
 * @property Vacancy[]|ActiveQuery $relVacancy
 * @property Groups[]|ActiveQuery $relGroups
 * @property int $count
 */
class RefVacancyStatuses extends CustomisableReference {
	public $menuCaption = 'Статусы вакансий';
	public $menuIcon = false;

	public $count = 0;//Псевдоаттрибут, заполняется при подсчёте среза по статусам вакансий

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_vacancy_statuses';
	}
	/**
	 * @return Vacancy[]|ActiveQuery
	 */
	public function getRelVacancy() {
		return $this->hasMany(Vacancy::class, ['status' => 'id']);
	}

	/**
	 * @return Groups[]|ActiveQuery
	 */
	public function getRelGroups() {
		return $this->hasMany(Groups::class, ['id' => 'group'])->via('relVacancy');
	}
}
