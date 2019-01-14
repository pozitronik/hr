<?php
declare(strict_types = 1);

namespace app\models\user_rights\rights\users;

use app\helpers\ArrayHelper;
use app\models\user_rights\UserRight;

/**
 * Class RightUserCreate
 * @package app\models\user_rights\rights
 */
class RightUserCreate extends UserRight {


	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return "Создание пользователя";
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return "Разрешает создать нового пользователя в системе";
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAccess(string $controller, string $action, array $actionParameters = []):?bool {
		$definedRules = [
			'UsersController' => [
				'actions' => [
					'create' => self::ACCESS_ALLOW
				]
			]
		];
		return ArrayHelper::getValue($definedRules, "{$controller}.actions.{$action}", parent::getAccess($controller, $action));

	}
}