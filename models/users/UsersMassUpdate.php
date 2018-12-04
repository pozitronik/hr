<?php
declare(strict_types = 1);

namespace app\models\users;

use yii\base\Model;

/**
 * Class UsersMassUpdate
 * Редактирование группы юзеров
 * @package app\models\users
 *
 * @property int[] $usersId
 * @property Users $virtualUser
 */
class UsersMassUpdate extends Model {
	private $usersId = [];
	private $virtualUser;

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
	 * Нужно подгрузить переданные постом свойства
	 * @param array $data
	 * @param null|string $formName
	 * @return bool
	 */
	public function load($data, $formName = null):bool {
		return parent::load($data, $formName) && $this->virtualUser->load($data[$this->virtualUser->classNameShort], '');
	}

	/**
	 * Применяет загруженные данные к пользователям, возвращает массив результатов
	 * return array
	 */
	public function apply():array{

	}

	/**
	 * Пытается загрузить массив id пользователей для массовой обработки + генерирует доступные наборы параметров
	 * @param int[] $selection
	 */
	public function loadSelection(array $selection):void {

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
}