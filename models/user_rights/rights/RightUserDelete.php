<?php
declare(strict_types = 1);

namespace app\models\user_rights\rights;

use app\helpers\ArrayHelper;
use app\models\user_rights\UserRight;

/**
 * Class RightUserDelete
 * @package app\models\user_rights\rights
 */
class RightUserDelete extends UserRight {


	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return "Удаление пользователя";
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return "Разрешает удалить пользователя из системы";
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAccess(string $controller, string $action):?bool {
		$definedRules = [
			'UsersController' => [
				'actions' => [
					'delete' => self::ACCESS_ALLOW
				]
			]
		];
		return ArrayHelper::getValue($definedRules, "{$controller}.actions.{$action}", self::ACCESS_UNDEFINED);

	}
}