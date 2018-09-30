<?php
declare(strict_types=1);

namespace app\models;

use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 */
class LoginForm extends Model {
	public $username;
	public $password;
	public $rememberMe = true;
	public $email;
	public $restore = false;


	/**
	 * @return array the validation rules.
	 */
	public function rules() {
		return [
			// username and password are both required
			[['username', 'password'], 'required'],
			// rememberMe must be a boolean value
			['rememberMe', 'boolean'],
			['email', 'email'],
			[['email'], 'required', 'when' => function($model){
				return $model->restore;
			}],
			// password is validated by validatePassword()
			['password', 'validatePassword'],
			['username', 'validateUsername']
		];
	}

	/**
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'username' => 'Логин',
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
	 * @return bool
	 */
	public function validatePassword($attribute): bool {
		return true;
	}

	/**
	 * Finds user by [[username]]
	 *
	 */
	public function getUser(): void {
		return null;
	}

	/**
	 * Validates the username.
	 * Пользователь может быть на модерации, таких пускать нельзя
	 *
	 * @param string $attribute the attribute currently being validated
	 * @internal param array $params the additional name-value pairs given in the rule
	 * @return bool
	 */
	public function validateUsername($attribute): bool {
		return true;
	}

	/**
	 * Logs in a user using the provided username and password.
	 * @return boolean whether the user is logged in successfully
	 */
	public function login(): bool {
		return true;
	}
}
