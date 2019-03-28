<?php
declare(strict_types = 1);

namespace app\modules\users\models\rights;

use app\modules\privileges\models\AccessMethods;
use app\modules\privileges\models\UserRight;
use Throwable;
use yii\base\InvalidConfigException;
use yii\base\Model;
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
	public static function checkActionAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		return parent::checkControllerAccessRule([
			'users/users' => [
				'actions' => [
					'create' => self::ACCESS_ALLOW
				]
			]
		], $controller, $action);
	}

	/**
	 * @param Model $model Модель, к которой проверяется доступ
	 * @param int|null $method Метод доступа (см. AccessMethods)
	 * @param array $actionParameters Дополнительный массив параметров (обычно $_GET)
	 * @return bool|null
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public static function checkMethodAccess(Model $model, ?int $method = AccessMethods::any, array $actionParameters = []):?bool {
		return parent::checkModelAccessRule([
			'users' => [
				AccessMethods::create => self::ACCESS_ALLOW
			]
		], $model, $method);
	}
}