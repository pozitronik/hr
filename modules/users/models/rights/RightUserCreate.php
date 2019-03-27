<?php
declare(strict_types = 1);

namespace app\modules\users\models\rights;

use app\helpers\ArrayHelper;
use app\modules\privileges\models\UserRight;
use yii\web\Controller;

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
	public function getAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		$definedRules = [
			'users/users' => [
				'actions' => [
					'create' => self::ACCESS_ALLOW
				]
			]
		];
		return ArrayHelper::getValue($definedRules, "{$controller->module->id}/{$controller->id}.actions.{$action}", parent::getAccess($controller, $action));
	}
}