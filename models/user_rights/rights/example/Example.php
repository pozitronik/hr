<?php
declare(strict_types = 1);

namespace app\models\user_rights\rights\example;

use app\models\user_rights\UserRight;

/**
 * Class Example
 * @package app\models\user_rights\rights\example
 */
class Example extends UserRight {

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return "Пример правила";
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return "Пример того, что может определять правило";
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAccess(string $controller, string $action):?bool {
		return 'UsersController' === $controller;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHidden():bool {
		return true;
	}
}