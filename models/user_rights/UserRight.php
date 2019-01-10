<?php
declare(strict_types = 1);

namespace app\models\user_rights;

use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * Class UserRight
 * Базовая модель права пользователя
 * @package app\models\user_rights
 *
 * @property string $id
 * @property array $actions
 */
class UserRight extends Model implements UserRightInterface {

	/**
	 * @return string
	 * @throws InvalidConfigException
	 */
	public function getId():string {
		return $this->formName();
	}

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return "Не определено";
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return "Описание не указано";
	}

	/**
	 * Набор действий, предоставляемых правом. Пока прототипирую
	 *
	 * @return array
	 */
	public function getActions():array {
		return [];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAccess(string $controller, string $action):?bool {
		return self::ACCESS_UNDEFINED;
	}
}