<?php

namespace app\models;

use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property \app\models\User|null $user
 */
class LoginForm extends Model {
	public $username;
	public $password;
	public $rememberMe = true;
	public $email;
	public $restore = false;

	private $_user = false;

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
	 */
	public function validatePassword($attribute) {
	//todo
	}

	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	public function getUser() {
		//todo
	}

	/**
	 * Validates the username.
	 * Пользователь может быть на модерации, таких пускать нельзя
	 *
	 * @param string $attribute the attribute currently being validated
	 * @internal param array $params the additional name-value pairs given in the rule
	 */
	public function validateUsername($attribute) {
		//todo
	}

	/**
	 * Logs in a user using the provided username and password.
	 * @return boolean whether the user is logged in successfully
	 */
	public function login() {
//		todo
	}
}
