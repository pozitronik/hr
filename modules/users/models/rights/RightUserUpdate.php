<?php
declare(strict_types = 1);

namespace app\modules\users\models\rights;

use app\modules\privileges\models\UserRight;
use app\components\pozitronik\core\interfaces\access\AccessMethods;
use Throwable;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\Controller;

/**
 * Class RightUserUpdate
 * @package app\models\user_rights\rights\users
 */
class RightUserUpdate extends UserRight {

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return "Редактирование профиля";
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return "Пользователь может вносить любые изменения в профили всех пользователей";
	}

	/**
	 * {@inheritDoc}
	 */
	public function checkActionAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		return $this->checkControllerAccessRule([
			'users/users' => [
				'actions' => [
					'update' => self::ACCESS_ALLOW
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
	public function checkMethodAccess(Model $model, ?int $method = AccessMethods::any, array $actionParameters = []):?bool {
		return $this->checkModelAccessRule([
			'users' => [
				AccessMethods::update => self::ACCESS_ALLOW
			]
		], $model, $method);
	}
}