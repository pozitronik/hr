<?php
declare(strict_types = 1);

namespace app\models\user_rights\rights;

use app\helpers\ArrayHelper;
use app\models\user_rights\UserRight;

/**
 * Class RightUserCreate
 * @package app\models\user_rights\rights
 */
class RightUserCreate implements UserRight {

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
		return "Право на заведение нового пользователя";
	}

	/**
	 * Набор действий, предоставляемых правом. Пока прототипирую
	 *
	 * @return array
	 */
	public function getActions():array {
		// TODO: Implement getActions() method.
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAccess(string $controller, string $action):?bool {
		$definedRules = [
			'UsersController' => [
				'actions' => [
					'create' => self::ACCESS_ALLOW
				]
			]
		];
		return ArrayHelper::getValue($definedRules, "{$controller}.actions.{$action}", self::ACCESS_UNDEFINED);

	}
}