<?php
declare(strict_types = 1);

namespace app\modules\users;

use pozitronik\core\models\core_module\CoreModule;


/**
 * Class UsersModule
 * @package app\modules\users
 */
class UsersModule extends CoreModule {
	/**
	 * {@inheritDoc}
	 */
	public function getName():string {
		return 'Поддержка пользователей';
	}
}
