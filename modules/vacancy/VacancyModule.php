<?php
declare(strict_types = 1);

namespace app\modules\vacancy;

use app\components\pozitronik\core\models\core_module\CoreModule;

/**
 * Class VacancyModule
 * @package app\modules\vacancy
 */
class VacancyModule extends CoreModule {
	/**
	 * {@inheritDoc}
	 */
	public function getName():string {
		return 'Вакансии и подбор персонала';
	}
}
