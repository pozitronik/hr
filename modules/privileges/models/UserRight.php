<?php
declare(strict_types = 1);

namespace app\modules\privileges\models;

use app\components\pozitronik\core\interfaces\access\AccessMethods;
use app\components\pozitronik\core\interfaces\access\UserRightInterface;
use app\components\pozitronik\helpers\ArrayHelper;
use Throwable;
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
 * @property string $module
 */
class UserRight extends Model implements UserRightInterface {
	protected $_module;//Регистрирующий модуль, заполняется при инициализации

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'name' => 'Название',
			'description' => 'Описание',
			'module' => 'Модуль'
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
	public function checkActionAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
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
	public function checkMethodAccess(Model $model, ?int $method = AccessMethods::any, array $actionParameters = []):?bool {
		return self::ACCESS_UNDEFINED;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFlag(int $flag):?bool {
		return self::ACCESS_UNDEFINED;
	}

	/**
	 * @return string
	 */
	public function getModule():string {
		return $this->_module;
	}

	/**
	 * @param string $module
	 */
	public function setModule(string $module):void {
		$this->_module = $module;
	}

	/**
	 * Макро для проверки разрешений доступов к экшенам контроллера. Перенесено сюда для микрооптимизации, поскольку один и тот же код использовался во многих рулесах
	 * @param array $accessRule
	 * @param Controller $controller
	 * @param string $action
	 * @return bool|null
	 * @throws Throwable
	 */
	protected function checkControllerAccessRule(array $accessRule, Controller $controller, string $action):?bool {
		/*Интересная фигня: мы не можем сослаться на $this->checkActionAccess, поскольку находимся в контексте вызывающего класса (и попадём в бесконечный цикл), мы не можем сослаться на parent (вот тут я хз), можем сослаться на self/static, но метод динамический. Я не стал копать, написал, как есть*/
		return ArrayHelper::getValue($accessRule, "{$controller->module->id}/{$controller->id}.actions.{$action}", self::ACCESS_UNDEFINED);
	}

	/**
	 * Макро для проверки разрешений доступов к методам моделей. Перенесено сюда для микрооптимизации, поскольку один и тот же код использовался во многих рулесах
	 * @param array $accessRule
	 * @param Model $model
	 * @param int|null $method
	 * @return bool|null
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	protected function checkModelAccessRule(array $accessRule, Model $model, ?int $method = AccessMethods::any):?bool {
		return ArrayHelper::getValue($accessRule, "{$model->formName()}.$method", self::ACCESS_UNDEFINED);
	}
}