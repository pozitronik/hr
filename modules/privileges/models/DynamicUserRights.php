<?php
declare(strict_types = 1);

namespace app\modules\privileges\models;

use app\models\core\LCQuery;
use app\models\core\traits\ARExtended;
use pozitronik\core\models\user_right\AccessMethods;
use pozitronik\core\models\user_right\UserRightInterface;
use pozitronik\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use pozitronik\core\models\core_module\PluginsSupport;
use app\models\core\CoreController;
use ReflectionException;
use Throwable;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

/**
 * This is the model class for table "sys_user_rights".
 *
 * @property int $id
 * @property string $name Название правила
 * @property string $description Описание
 * @property bool $deleted
 *
 * @property array $rules Набор разрешений правила (то, что уходит в БД в JSON)
 * @property ActionAccess[] $actionsAccessMap Массив разрешений доступов к экшонам
 * @property-read ArrayDataProvider $actionsAccessProvider Провайдер для отображения списка экшонов
 * @property-read string $module
 */
class DynamicUserRights extends ActiveRecord implements UserRightInterface {
	use ARExtended;

	protected $_module;//Регистрирующий модуль, заполняется при инициализации
	protected $_actionsAccessMap = [];
	private $_rules;//для обхода прямой модификации $rules
	private $ruleActionsIndexName = 'actionAccess';//имя секции для сохранения правил доступа к экшонам
	private $ruleMethodsIndexName = 'methodAccess';//имя секции для сохранения правил доступа к экшонам

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_user_rights';
	}

	/**
	 * @return LCQuery
	 */
	public static function find():LCQuery {
		return new LCQuery(static::class);
	}

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		parent::init();
		$this->loadActionsMap(PluginsSupport::GetAllControllersPaths());//Загрузит только карту экшенов БЕЗ заполнения правилами: атрибут правил ещё не заполнен. Но вызывать нужно, чтобы строилась карта при создании моделей.
	}

	/**
	 * {@inheritDoc}
	 */
	public function afterFind() {
		$this->loadActionsMap(PluginsSupport::GetAllControllersPaths());//Загрузит карту и уже заполнит правилами
	}

	/**
	 * Загружает карту всех существующих экшенов во всех переданных путях к WigetableController
	 * @param array $fromControllersPaths
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 */
	public function loadActionsMap(array $fromControllersPaths):void {
		foreach ($fromControllersPaths as $moduleId => $controllerPath) {
			$controllers = CoreController::GetControllersList($controllerPath, $moduleId, [CoreController::class]);
			foreach ($controllers as $controller) {
				$actions = CoreController::GetControllerActions($controller);
				foreach ($actions as $action) {
					$actionAccess = new ActionAccess([
						'moduleId' => $moduleId,
						'controllerId' => $controller->id,
						'actionName' => mb_strtolower($action),
						'state' => $this->checkActionAccess($controller, mb_strtolower($action))
					]);
					$this->_actionsAccessMap[$actionAccess->id] = $actionAccess;
				}
			}
		}
	}

	/**
	 * @return ActionAccess[]
	 */
	public function getActionsAccessMap():array {
		return $this->_actionsAccessMap;
	}

	/**
	 * @param ActionAccess[] $actionsAccessMap
	 */
	public function setActionsAccessMap(array $actionsAccessMap):void {
		foreach ($actionsAccessMap as $accessItem => $value) {
			/** @var int $accessItem */
			$this->_actionsAccessMap[$accessItem]->state = $value;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function beforeValidate():bool {
		$this->prepareAccessMap();
		return parent::beforeValidate();
	}

	private function prepareAccessMap() {
		$this->_rules[$this->ruleActionsIndexName] = [];
		foreach ($this->actionsAccessMap as $item) {
			if (null !== $item->state) {
				$this->_rules[$this->ruleActionsIndexName][$item->moduleId][$item->controllerId][$item->actionName] = $item->state;
			}
		}
		$this->rules = $this->_rules;
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name', 'actionsAccessMap', 'rules'], 'required'],
			[['actionsAccessMap', 'rules'], 'safe'],
			[['name', 'description'], 'string', 'max' => 255],
			[['name'], 'unique'],
			[['deleted'], 'boolean']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'rules' => 'Набор разрешений правила',
			'description' => 'Описание',
			'module' => 'Модуль',
			'deleted' => 'Удалено'
		];
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
	 * Уникальный идентификатор (подразумевается имя класса)
	 * @return string
	 * @throws InvalidConfigException
	 */
	public function getId():string {
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
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		return $this->description;
	}

	/**
	 * @param Controller $controller Экземпляр класса контроллера
	 * @param string $action Имя экшена
	 * @param array $actionParameters Дополнительный массив параметров (обычно $_GET)
	 * @return bool|null Одна из констант доступа
	 * @throws Throwable
	 */
	public function checkActionAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		return ArrayHelper::getValue($this->rules, "{$this->ruleActionsIndexName}.{$controller->module->id}.{$controller->id}.{$action}", self::ACCESS_UNDEFINED);
	}

	/**
	 * @param Model $model Модель, к которой проверяется доступ
	 * @param int|null $method Метод доступа (см. AccessMethods)
	 * @param array $actionParameters Дополнительный массив параметров (обычно $_GET)
	 * @return bool|null
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function checkMethodAccess(Model $model, ?int $method = AccessMethods::any, array $actionParameters = []):?bool {
		return ArrayHelper::getValue($this->rules, "{$this->ruleMethodsIndexName}.{$model->formName()}.{$method}", self::ACCESS_UNDEFINED);
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
	 * Для возможностей, которые можно и нужно включать только флагамм + прототипирование
	 * @param int $flag
	 * @return null|bool
	 */
	public function getFlag(int $flag):?bool {
		return self::ACCESS_UNDEFINED;
	}

	/**
	 * @return ArrayDataProvider
	 */
	public function getActionsAccessProvider():ArrayDataProvider {
		return new ArrayDataProvider([
			'allModels' => $this->_actionsAccessMap,
			'pagination' => false,
			'sort' => [
				'attributes' => ['moduleId', 'controllerId', 'actionName', 'state']
			]
		]);
	}

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getModule():string {
		return 'Динамическое правило';/*Оставляем так; настоящее имя модуля возвращается только статическими правилами, динамика же, хоть и находидся кодово в модуле, логически обособлена и на модули не завязана*/
	}
}
