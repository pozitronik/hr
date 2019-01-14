<?php
declare(strict_types = 1);

namespace app\models\user_rights\rights\users;

use app\helpers\ArrayHelper;
use app\models\user\CurrentUser;
use app\models\user_rights\UserRight;

/**
 * Class RightUserUpdateSelf
 * @package app\models\user_rights\rights\users
 */
class RightUserUpdateSelf extends UserRight {

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return "Редактирование своего профиля";
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return "Пользователь может вносить любые изменения в собственный профиль";
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAccess(string $controller, string $action, array $actionParams = []):?bool {
		$definedRules = [
			'UsersController' => [
				'actions' => [
					'update' => CurrentUser::Id() === ArrayHelper::getValue($actionParams, 'id')?self::ACCESS_ALLOW:self::ACCESS_DENY
				]
			]
		];

		return ArrayHelper::getValue($definedRules, "{$controller}.actions.{$action}", parent::getAccess($controller, $action));

	}
}