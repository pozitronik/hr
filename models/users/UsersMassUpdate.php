<?php
declare(strict_types = 1);

namespace app\models\users;

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use app\widgets\alert\AlertModel;
use yii\base\Model;

/**
 * Class UsersMassUpdate
 * Редактирование группы юзеров
 * @package app\models\users
 *
 * @property int[] $usersId
 * @property Users $virtualUser
 * @property-read Users[] $users
 */
class UsersMassUpdate extends Model {
	private $usersId = [];
	private $virtualUser;
	private $relCompetencies; //Поскольку модель пользователя сразу применяет переданные компетенции, то в вирутальную модель вводим переменную для хранения.

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
			[['usersId', 'virtualUser'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'usersId' => 'Пользователи'
		];
	}

	/**
	 * Нужно подгрузить переданные постом свойства
	 * @param array $data
	 * @param null|string $formName
	 * @return bool
	 */
	public function load($data, $formName = null):bool {
		if (parent::load($data, $formName) && $this->virtualUser->load($data[$this->virtualUser->classNameShort], '')) {
			/*Параметры, которые в модели пользователя применяются без промежуточного сохранения нам нужно всё-таки хранить*/
			$this->relCompetencies = ArrayHelper::getValue($data, "{$this->virtualUser->classNameShort}.relCompetencies");
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
		if (!empty($this->relCompetencies)) {
			$paramsArray['relCompetencies'] = $this->relCompetencies;
		}

		foreach ($this->usersId as $userId) {
			if (false !== $user = Users::findModel($userId)) {
				if ($user->updateUser($paramsArray)) {
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
	 * @param int $groupId
	 */
	public function loadGroupSelection(int $groupId) {
		if (false !== $group = Groups::findModel($groupId)) {
			$this->usersId = ArrayHelper::getColumn($group->relUsers, 'id');
			return true;
		}
		return false;
	}

	/**
	 * Пытается загрузить массив id пользователей для массовой обработки + генерирует доступные наборы параметров
	 * @param int[] $selection
	 */
	public function loadSelection(array $selection):bool {
		if ([] !== $users = Users::findModels($selection)) {
			$this->usersId = $selection;
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
	 * @param array $usersId
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
	 * @param mixed $virtualUser
	 */
	public function setVirtualUser($virtualUser):void {
		$this->virtualUser = $virtualUser;
	}

	/**
	 * @return Users[]
	 */
	public function getUsers():array {
		return Users::findModels($this->usersId);
	}

}