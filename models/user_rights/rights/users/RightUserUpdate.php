<?php
declare(strict_types = 1);

namespace app\models\user_rights\rights\users;

use app\helpers\ArrayHelper;
use app\models\user_rights\AccessMethods;
use app\models\user_rights\UserRight;
use Throwable;
use yii\base\InvalidConfigException;
use yii\base\Model;

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
	public function getAccess(string $controller, string $action, array $actionParameters = []):?bool {
		$definedRules = [
			'UsersController' => [
				'actions' => [
					'update' => self::ACCESS_ALLOW
				]
			]
		];

		return ArrayHelper::getValue($definedRules, "{$controller}.actions.{$action}", parent::getAccess($controller, $action));
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
		$definedRules = [
			'users' => [
				AccessMethods::update => self::ACCESS_ALLOW
			]
		];

		return ArrayHelper::getValue($definedRules, "{$model->formName()}.$method", parent::canAccess($model, $method, $actionParameters));
	}
}