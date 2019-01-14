<?php
declare(strict_types = 1);

namespace app\models\user_rights\rights\users;

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
	public function getAccess(string $controller, string $action, array $actionParameters = []):?bool {
		$definedRules = [
			'UsersController' => [
				'actions' => [
					'delete' => self::ACCESS_ALLOW
				]
			]
		];
		return ArrayHelper::getValue($definedRules, "{$controller}.actions.{$action}", parent::getAccess($controller, $action));

	}
}