<?php
declare(strict_types = 1);

namespace app\modules\privileges\models\rights\groups;

use app\helpers\ArrayHelper;
use app\modules\privileges\models\UserRight;
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
	public function getAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		$definedRules = [
			'groups' => [
				'actions' => [
					'create' => self::ACCESS_ALLOW
				]
			]
		];
		return ArrayHelper::getValue($definedRules, "{$controller->id}.actions.{$action}", parent::getAccess($controller, $action));
	}
}