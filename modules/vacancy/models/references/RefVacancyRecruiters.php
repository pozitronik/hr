<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models\references;

use app\components\pozitronik\references\models\CustomisableReference;

/**
 * Class RefVacancyRecruiters
 * @package app\modules\vacancy\models\references
 */
class RefVacancyRecruiters extends CustomisableReference {
	public $menuCaption = 'Рекрутеры';
	public $menuIcon = false;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_vacancy_recruiters';
	}

}
