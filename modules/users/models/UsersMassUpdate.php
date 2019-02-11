<?php
declare(strict_types = 1);

namespace app\modules\users\models;

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use app\widgets\alert\AlertModel;
use Throwable;
use yii\base\Model;

/**
 * Class UsersMassUpdate
 * Редактирование группы юзеров
 * @package app\models\users
 *
 * @property int[] $usersId
 * @property int[] $usersIdSelected id пользователей, установленных в фильтре.
 * @property Users $virtualUser
 * @property-read Users[] $users
 * @property-read Users[] $usersSelected
 */
class UsersMassUpdate extends Model {
	private $usersId = [];
	private $usersIdSelected = [];
	private $virtualUser;
	private $relGroups; //Поскольку модель пользователя сразу применяет переданные группы, то в вирутальную модель вводим переменную для хранения.
	private $relDynamicAttributes; //Поскольку модель пользователя сразу применяет переданные атрибуты, то в вирутальную модель вводим переменную для хранения.
	private $dropGroups;
	private $dropUsersAttributes;

	/**
	 * @inheritdoc
	 */
	public function init():void {
		parent::init();
		$this->virtualUser = new Users();
	}

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['usersId', 'usersIdSelected', 'virtualUser'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'usersId' => 'Пользователи',
			'usersIdSelected' => 'Пользователи'
		];
	}

	/**
	 * Нужно подгрузить переданные постом свойства
	 * @param array $data
	 * @param null|string $formName
	 * @return bool
	 * @throws Throwable
	 */
	public function load($data, $formName = null):bool {
		if (parent::load($data, $formName) && $this->virtualUser->load($data[$this->virtualUser->formName()], '')) {
			/*Параметры, которые в модели пользователя применяются без промежуточного сохранения нам нужно всё-таки хранить*/
			$this->relDynamicAttributes = ArrayHelper::getValue($data, "{$this->virtualUser->formName()}.relDynamicAttributes");
			$this->relGroups = ArrayHelper::getValue($data, "{$this->virtualUser->formName()}.relGroups");
			$this->dropUsersAttributes = ArrayHelper::getValue($data, "{$this->virtualUser->formName()}.dropUsersAttributes");
			$this->dropGroups = ArrayHelper::getValue($data, "{$this->virtualUser->formName()}.dropGroups");
			return true;
		}
		return false;
	}

	/**
	 * Применяет загруженные данные к пользователям, возвращает массив результатов
	 * return array
	 */
	public function apply():array {
		$statistic = [];
		$paramsArray = [];
		if (!empty($this->relGroups)) $paramsArray['relGroups'] = $this->relGroups;
		if (!empty($this->relDynamicAttributes)) $paramsArray['relDynamicAttributes'] = $this->relDynamicAttributes;
		if (!empty($this->dropGroups)) $paramsArray['dropGroups'] = $this->dropGroups;
		if (!empty($this->dropUsersAttributes)) $paramsArray['dropUsersAttributes'] = $this->dropUsersAttributes;

		foreach ($this->usersIdSelected as $userId) {
			if (null !== $user = Users::findModel($userId)) {
				if ($user->updateModel($paramsArray)) {
					$statistic[] = [
						'id' => $userId,
						'username' => $user->username,
						'status' => "Пользователь {$user->username} изменён",
						'error' => false
					];
				} else {
					$statistic[] = [
						'id' => $userId,
						'username' => $user->username,
						'status' => "Пользователь {$user->username} не изменён, ошибки: ".AlertModel::ArrayErrors2String($user->errors),
						'error' => true
					];
				}
			} else {
				$statistic[] = [
					'id' => $userId,
					'username' => false,
					'status' => "Пользователь id={$userId} не найден",
					'error' => true
				];
			}
		}
		return $statistic;
	}

	/**
	 * Пытается загрузить пользователей группы для массовой обработки + генерирует доступные наборы параметров
	 * @param int|null $groupId
	 * @param bool $hierarchy Подгрузить пользователей вниз по иерархии
	 * @return bool
	 * @throws Throwable
	 */
	public function loadGroupSelection(?int $groupId = null, bool $hierarchy = false):bool {
		if (null !== $group = Groups::findModel($groupId)) {
			$this->usersId = $hierarchy?ArrayHelper::getColumn($group->getRelUsersHierarchy()->all(), 'id'):ArrayHelper::getColumn($group->relUsers, 'id');
			$this->usersIdSelected = $this->usersId;//При загрузке пользователей из группы выбираем сразу всех
			return true;
		}
		return false;
	}

	/**
	 * Пытается загрузить массив id пользователей для массовой обработки + генерирует доступные наборы параметров
	 * @param int[]|null $selection
	 * @return bool
	 * @throws Throwable
	 */
	public function loadSelection(?array $selection):bool {
		if (null === $selection) return false;
		if ([] !== $users = Users::findModels($selection)) {
			$this->usersIdSelected = ArrayHelper::getColumn($users, 'id');
		}
		return false;
	}

	/**
	 * @return array
	 */
	public function getUsersId():array {
		return $this->usersId;
	}

	/**
	 * @param int[] $usersId
	 */
	public function setUsersId(array $usersId):void {
		$this->usersId = $usersId;
	}

	/**
	 * @return mixed
	 */
	public function getVirtualUser() {
		return $this->virtualUser;
	}

	/**
	 * @param Users $virtualUser
	 */
	public function setVirtualUser(Users $virtualUser):void {
		$this->virtualUser = $virtualUser;
	}

	/**
	 * @return Users[]
	 * @throws Throwable
	 */
	public function getUsers():array {
		return Users::findModels($this->usersId);
	}

	/**
	 * @return Users[]
	 * @throws Throwable
	 */
	public function getUsersSelected():array {
		return Users::findModels($this->usersIdSelected);
	}

	/**
	 * @return bool|int[]
	 */
	public function getUsersIdSelected():array {
		return $this->usersIdSelected;
	}

	/**
	 * @param bool|int[] $usersIdSelected
	 */
	public function setUsersIdSelected(array $usersIdSelected):void {
		$this->usersIdSelected = $usersIdSelected;
	}

}