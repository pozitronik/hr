<?php
declare(strict_types = 1);

namespace app\modules\privileges;

use pozitronik\core\models\core_module\CoreModule;


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
