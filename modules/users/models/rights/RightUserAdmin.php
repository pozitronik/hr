<?php
declare(strict_types = 1);

namespace app\modules\users\models\rights;

use app\modules\privileges\models\UserRight;
use app\modules\users\models\Users;
use app\components\pozitronik\core\interfaces\access\AccessMethods;
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
	public function checkActionAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		return ('users/users' === "{$controller->module->id}/$controller->id")?self::ACCESS_ALLOW:parent::checkActionAccess($controller, $action, $actionParameters);
	}

	/**
	 * @param Model $model Модель, к которой проверяется доступ
	 * @param int|null $method Метод доступа (см. AccessMethods)
	 * @param array $actionParameters Дополнительный массив параметров (обычно $_GET)
	 * @return bool|null
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public  function checkMethodAccess(Model $model, ?int $method = AccessMethods::any, array $actionParameters = []):?bool {
		return ($model->formName() === (new Users())->formName())?self::ACCESS_ALLOW:parent::checkMethodAccess($model, $method, $actionParameters);
	}
}