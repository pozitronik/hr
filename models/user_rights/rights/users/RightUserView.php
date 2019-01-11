<?php
declare(strict_types = 1);

namespace app\models\user_rights\rights\users;

use app\helpers\ArrayHelper;
use app\models\user_rights\UserRight;

/**
 * Class RightUserView
 * @package app\models\user_rights\rights\users
 */
class RightUserView extends UserRight {

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return "Просмотр пользователей";
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return "Доступ к списку всех пользователей в системе";
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAccess(string $controller, string $action):?bool {
		$definedRules = [
			'UsersController' => [
				'actions' => [
					'index' => self::ACCESS_ALLOW
				]
			]
		];
		return ArrayHelper::getValue($definedRules, "{$controller}.actions.{$action}", parent::getAccess($controller, $action));

	}
}