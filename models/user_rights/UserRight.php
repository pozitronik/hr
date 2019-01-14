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
	public function getAccess(string $controller, string $action, array $actionParams = []):?bool {
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
}