<?php
declare(strict_types = 1);

namespace app\modules\groups\models\rights;

use app\modules\privileges\models\UserRight;
use pozitronik\core\interfaces\access\AccessMethods;
use yii\base\Model;
use yii\web\Controller;

/**
 * Class RightGroupCreate
 * @package app\modules\privileges\models\rights\groups
 */
class RightGroupCreate extends UserRight {

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return "Создание группы";
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return "Разрешает создать новую группу в системе";
	}

	/**
	 * {@inheritDoc}
	 */
	public function checkActionAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		return $this->checkControllerAccessRule([
			'groups' => [
				'actions' => [
					'create' => self::ACCESS_ALLOW
				]
			]
		], $controller, $action);
	}

	/**
	 * {@inheritDoc}
	 */
	public function checkMethodAccess(Model $model, ?int $method = AccessMethods::any, array $actionParameters = []):?bool {
		return $this->checkModelAccessRule([
			'groups' => [
				AccessMethods::create => self::ACCESS_ALLOW
			]
		], $model, $method);
	}
}