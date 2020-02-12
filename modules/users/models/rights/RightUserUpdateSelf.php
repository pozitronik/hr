<?php
declare(strict_types = 1);

namespace app\modules\users\models\rights;

use pozitronik\core\models\user_right\AccessMethods;
use pozitronik\helpers\ArrayHelper;
use app\models\user\CurrentUser;
use app\modules\privileges\models\UserRight;
use Throwable;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\Controller;

/**
 * Class RightUserUpdateSelf
 * @package app\models\user_rights\rights\users
 */
class RightUserUpdateSelf extends UserRight {

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return "Редактирование своего профиля";
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return "Пользователь может вносить любые изменения в собственный профиль";
	}

	/**
	 * {@inheritDoc}
	 */
	public function checkActionAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		return $this->checkControllerAccessRule([
			'users/users' => [
				'actions' => [
					'update' => CurrentUser::Id() === (int)ArrayHelper::getValue($actionParameters, 'id')?self::ACCESS_ALLOW:self::ACCESS_UNDEFINED,
					'profile' => CurrentUser::Id() === (int)ArrayHelper::getValue($actionParameters, 'id')?self::ACCESS_ALLOW:self::ACCESS_UNDEFINED
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
			'Users' => [
				AccessMethods::update => CurrentUser::Id() === (int)ArrayHelper::getValue($actionParameters, 'id')?self::ACCESS_ALLOW:self::ACCESS_UNDEFINED
			]
		], $model, $method);
	}
}