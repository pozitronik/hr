<?php
declare(strict_types=1);

namespace app\models\site;

use app\helpers\Date;
use app\models\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 *
 * @property User|null $user
 */
class LoginForm extends Model {
	public $login;
	public $password;
	public $rememberMe = true;
	public $email;
	public $restore = false;

	private $_user = false;

	/**
	 * @return array the validation rules.
	 */
	public function rules():array {
		return [
			[['login', 'password'], 'required'],
			['rememberMe', 'boolean'],
			['email', 'email'],
			['password', 'validatePassword'],
			['Login', 'validateLogin']
		];
	}

	/**
	 * @return array
	 */
	public function attributeLabels():array {
		return [
			'login' => 'Логин',
			'password' => 'Пароль',
			'rememberMe' => 'Запомнить'
		];
	}

	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @internal param array $params the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute): void {
		if (!$this->hasErrors()) {
			$user = $this->getUser();
			if (!$user || !$user->validatePassword($this->password)) {
				$this->addError($attribute, 'Неправильные логин или пароль.');
			}
		}
	}

	/**
	 * @return User|null
	 */
	public function getUser(): ?User {
		if (false === $this->_user) {
			$this->_user = User::findByLogin($this->login);
		}
		return $this->_user;
	}

	/**
	 * Validates the username.
	 * Пользователь может быть на модерации, таких пускать нельзя
	 *
	 * @param string $attribute the attribute currently being validated
	 * @internal param array $params the additional name-value pairs given in the rule
	 */
	public function validateLogin($attribute): void {
		if (!$this->hasErrors()) {
			$user = $this->getUser();
			if (null !== $user && $user->CurrentUser->deleted) {
				$this->addError($attribute, 'Пользователь заблокирован');
			}
		}
	}

	/**
	 * Logs in a user using the provided username and password.
	 * @return boolean whether the user is logged in successfully
	 */
	public function doLogin(): bool {
		return ($this->validate() && Yii::$app->user->login($this->getUser(), $this->rememberMe?Date::SECONDS_IN_MONTH:0));
	}
}
