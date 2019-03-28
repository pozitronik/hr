<?php
declare(strict_types = 1);

namespace app\modules\privileges\models\rights\admin;

use app\modules\privileges\models\UserRight;
use yii\web\Controller;

/**
 * Class ServiceAccess
 * @package app\models\user_rights\rights\admin
 */
class ServiceAccess extends UserRight {

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return "Доступ к сервисному меню";
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return '<i class="fa fa-radiation-alt"></i> Доступ к сервисной странице';
	}

	/**
	 * {@inheritDoc}
	 */
	public function checkActionAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		return ('service/service' === "{$controller->module->id}/{$controller->id}")?self::ACCESS_ALLOW:parent::checkActionAccess($controller, $action, $actionParameters);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFlag(int $flag):?bool {
		return (self::FLAG_SERVICE === $flag)?self::ACCESS_ALLOW:parent::getFlag($flag);
	}
}