<?php
declare(strict_types = 1);

namespace app\modules\privileges\models;

use app\models\core\core_module\PluginsSupport;
use app\models\core\Magic;
use app\models\core\WigetableController;
use yii\base\Model;

/**
 * Class ActionMap
 * @package app\modules\privileges\models
 * @property-read string $id
 * @property string $actionName
 * @property string $controllerId
 * @property string $moduleId
 * @property null|bool $state
 *
 * @property-read WigetableController $controller
 * @property-read string $moduleDescription
 * @property-read string $controllerDescription
 * @property-read string $actionDescription
 */
class ActionAccess extends Model {
	private $_actionName;
	private $_controllerId;
	private $_moduleId;
	private $_state;

	/**
	 * {@inheritDoc}
	 */
	public function attributeLabels():array {
		return [
			'actionName' => 'Действие',
			'controllerId' => 'Контроллер',
			'moduleId' => 'Модуль',
			'state' => 'Разрешить'
		];
	}

	/**
	 * @return string
	 */
	public function getActionName():string {
		return $this->_actionName;
	}

	/**
	 * @param string $name
	 */
	public function setActionName(string $name):void {
		$this->_actionName = $name;
	}

	/**
	 * @return string
	 */
	public function getControllerId():string {
		return $this->_controllerId;
	}

	/**
	 * @param string $controllerId
	 */
	public function setControllerId(string $controllerId):void {
		$this->_controllerId = $controllerId;
	}

	/**
	 * @return string
	 */
	public function getModuleId():string {
		return $this->_moduleId;
	}

	/**
	 * @param string $moduleId
	 */
	public function setModuleId(string $moduleId):void {
		$this->_moduleId = $moduleId;
	}

	/**
	 * @return bool|null
	 */
	public function getState():?bool {
		return $this->_state;
	}

	/**
	 * @param bool|null $state
	 */
	public function setState(?bool $state):void {
		$this->_state = $state;
	}

	/**
	 * @return string
	 */
	public function getId():string {
		return "{$this->moduleId}.{$this->controllerId}.{$this->actionName}";
	}

	/**
	 * @return string
	 */
	public function getModuleDescription():string {
		return PluginsSupport::GetName($this->moduleId);
	}

	/**
	 * @return string
	 */
	public function getControllerDescription():string {
		return false !== $this->controller->menuCaption?$this->controller->menuCaption:$this->controllerId;
	}

	/**
	 * @return string
	 */
	public function getActionDescription():string {
		return $this->actionName;
	}

	/**
	 * @return WigetableController
	 */
	public function getController():WigetableController {
		return WigetableController::GetControllerByControllerId($this->controllerId, $this->moduleId);
	}

}