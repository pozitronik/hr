<?php
declare(strict_types = 1);

namespace app\modules\grades;

use app\models\core\core_module\CoreModule;

/** @noinspection EmptyClassInspection */

/**
 * Class GradesModule
 * @package app\modules\grades
 */
class GradesModule extends CoreModule {

	/**
	 * {@inheritDoc}
	 */
	public function getName():string {
		return 'Поддержка грейдов';
	}

}
