<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models\references;

use app\modules\groups\models\Groups;
use app\components\pozitronik\references\models\CustomisableReference;
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
	 * @return ActiveQuery
	 */
	public function getRelVacancy():ActiveQuery {
		return $this->hasMany(Vacancy::class, ['status' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelGroups():ActiveQuery {
		return $this->hasMany(Groups::class, ['id' => 'group'])->via('relVacancy');
	}
}
