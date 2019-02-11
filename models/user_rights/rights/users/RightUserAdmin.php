<?php
declare(strict_types = 1);

namespace app\models\user_rights\rights\users;

use app\models\user_rights\AccessMethods;
use app\models\user_rights\UserRight;
use app\models\users\Users;
use Throwable;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\Controller;

/**
 * Class RightUserAdmin
 * @package app\models\user_rights\rights\users
 */
class RightUserAdmin extends UserRight {

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return "Управление пользователями";
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return "Неограниченный доступ к управлению всеми пользователями в системе";
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		return ('users/users' === $controller->id)?self::ACCESS_ALLOW:parent::getAccess($controller, $action, $actionParameters);
	}

	/**
	 * @param Model $model Модель, к которой проверяется доступ
	 * @param int|null $method Метод доступа (см. AccessMethods)
	 * @param array $actionParameters Дополнительный массив параметров (обычно $_GET)
	 * @return bool|null
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public function canAccess(Model $model, ?int $method = AccessMethods::any, array $actionParameters = []):?bool {
		return ($model->formName() === (new Users())->formName())?self::ACCESS_ALLOW:parent::canAccess($model, $method, $actionParameters);
	}
}