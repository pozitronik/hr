<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models\references;


use app\modules\references\models\CustomisableReference;

/**
 * Class RefVacancyStatuses
 * @package app\modules\salary\models\vacancy
 */
class RefVacancyStatuses extends CustomisableReference {
	public $menuCaption = 'Статусы вакансий';
	public $menuIcon = false;


	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_vacancy_statuses';
	}

}
