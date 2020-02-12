<?php
declare(strict_types = 1);

namespace app\modules\home;

use pozitronik\core\models\core_module\CoreModule;

/**
 * Class HomeModule
 * @package app\modules\home
 */
class HomeModule extends CoreModule {
	/**
	 * {@inheritDoc}
	 */
	public function getName():string {
		return 'Домашний модуль (точка входа всех пользователей)';
	}
}
