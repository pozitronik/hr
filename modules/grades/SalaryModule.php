<?php
declare(strict_types = 1);

namespace app\modules\grades;

use app\models\core\core_module\CoreModule;


/**
 * Class SalaryModule
 * @package app\modules\grades
 *
 * @property string $name
 */
class SalaryModule extends CoreModule {

	/**
	 * {@inheritDoc}
	 */
	public function getName():string {
		return 'Зарплатный модуль';
	}

}