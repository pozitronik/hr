<?php
declare(strict_types = 1);

namespace app\modules\privileges\models\rights\users;

use app\helpers\ArrayHelper;
use app\modules\privileges\models\UserRight;
use yii\web\Controller;

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
	public function getAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		$definedRules = [
			'users/users' => [
				'actions' => [
					'index' => self::ACCESS_ALLOW
				]
			]
		];
		return ArrayHelper::getValue($definedRules, "{$controller->id}.actions.{$action}", parent::getAccess($controller, $action));

	}
}