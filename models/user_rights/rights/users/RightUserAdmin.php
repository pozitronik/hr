<?php
declare(strict_types = 1);

namespace app\models\user_rights\rights\users;

use app\models\user_rights\UserRight;

/**
 * Class RightUserAdmin
 * @package app\models\user_rights\rights\users
 */
class RightUserAdmin extends UserRight {

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return "Управление пользователями";
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return "Доступ к управлению пользователями в системе";
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAccess(string $controller, string $action, array $actionParameters = []):?bool {
		return 'UsersController' === $controller;

	}
}