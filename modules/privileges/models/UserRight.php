<?php
declare(strict_types = 1);

namespace app\modules\privileges\models;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\Controller;

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
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'name' => 'Название',
			'description' => 'Описание'
		];
	}

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
	public function getAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		return self::ACCESS_UNDEFINED;
	}

	/**
	 * Магическое свойство, необходимое для сравнения классов, например
	 * Предполагается, что будет использоваться имя класса
	 * @return string
	 * @throws InvalidConfigException
	 */
	public function __toString():string {
		return $this->formName();
	}

	/**
	 * Вернуть true, если правило не должно быть доступно в выбиралке
	 * @return bool
	 */
	public function getHidden():bool {
		return false;
	}

	/**
	 * @param Model $model Модель, к которой проверяется доступ
	 * @param int|null $method Метод доступа (см. AccessMethods)
	 * @param array $actionParameters Дополнительный массив параметров (обычно $_GET)
	 * @return bool|null
	 */
	public function canAccess(Model $model, ?int $method = AccessMethods::any, array $actionParameters = []):?bool {
		return self::ACCESS_UNDEFINED;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFlag(int $flag):?bool {
		return self::ACCESS_UNDEFINED;
	}
}