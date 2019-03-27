<?php
declare(strict_types = 1);

namespace app\modules\privileges\models\rights\example;

use app\modules\privileges\models\UserRight;
use yii\db\ActiveQuery;
use yii\web\Controller;

/**
 * Class Example
 * @package app\models\user_rights\rights\example
 */
class Example extends UserRight {

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return "Пример правила";
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return "Пример того, что может определять правило";
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		return 'UsersController' === $controller;
	}

	/**
	 * @param ActiveQuery $query
	 */
	public static function SetGroupsScope(ActiveQuery $query):void {
		$query->active();
		$query->where('sys_groups.id<10');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHidden():bool {
		return true;
	}
}