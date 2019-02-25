<?php
declare(strict_types = 1);

namespace app\modules\users;

use app\models\core\core_module\CoreModule;

/** @noinspection EmptyClassInspection */

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
