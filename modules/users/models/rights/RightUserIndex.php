<?php
declare(strict_types = 1);

namespace app\modules\users\models\rights;

use app\modules\privileges\models\UserRight;
use yii\web\Controller;

/**
 * Class RightUserIndex
 * @package app\modules\users\models\rights
 */
class RightUserIndex extends UserRight {

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
	public function checkActionAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		return parent::checkControllerAccessRule([
			'users/users' => [
				'actions' => [
					'index' => self::ACCESS_ALLOW,
					'profile' => self::ACCESS_ALLOW
				]
			]
		], $controller, $action);

	}
}