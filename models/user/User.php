<?php
declare(strict_types=1);

namespace app\models;

use app\models\users\Users;
use yii\base\BaseObject;
use yii\web\IdentityInterface;

/**
 * Class User
 * Расширяет свойства Yii::$app->user->identity
 * @package app\models
 */
class User extends BaseObject implements IdentityInterface {
	private static $users = [];
	public $id;
	public $username;
	public $password;
	public $salt;
	public $authKey;
	public $accessToken;
	/** @var Users $CurrentUser */
	public $CurrentUser;

	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id) {
		self::fillUserData(Users::findModel($id));
		return new static(self::$users);
	}

	/**
	 * @param Users|array $data
	 * Расширяет свойства Yii::$app->user->identity, абсолютно идентично Users::CurrentUser
	 * используем вместо Users::CurrentUser, т.к. работает быстрее в счёт кеширования
	 */
	public static function fillUserData($data = []) {
		self::$users = $data;
		if (!empty($data)) {
			self::$users = [
				'CurrentUser' => $data,
				'id' => $data->id,
				'login' => $data->login,
				'password' => $data->password,
				'salt' => $data->salt
			];
		}
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null) {
		self::fillUserData(Users::findOne(['accessToken' => $token]));
		return new static(self::$users);
	}

	/**
	 * @param string $login
	 * @return User
	 */
	public static function findByLogin(string $login): User {
		self::fillUserData(Users::findByLogin($login));
		return new static(self::$users);
	}

	/**
	 * @inheritdoc
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey():string {
		return $this->authKey;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey):bool {
		return $this->authKey === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password): bool {
		return (null === $this->salt)?$this->password === $password:sha1($password.$this->salt) === $this->password;
	}
}
