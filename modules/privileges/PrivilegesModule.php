<?php
declare(strict_types = 1);

namespace app\modules\privileges;

use app\models\core\core_module\CoreModule;

/** @noinspection EmptyClassInspection */

/**
 * Class PrivilegesModule
 * @package app\modules\privileges
 */
class PrivilegesModule extends CoreModule {
	/**
	 * {@inheritDoc}
	 */
	public function getName():string {
		return 'Управление доступами';
	}
}
