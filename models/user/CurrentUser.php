<?php

namespace app\models;

/**
 * Class CurrentUser
 * @package app\models
 */
class CurrentUser extends User {

	/**
	 * Отправляет на домашнюю страницу
	 * @return string
	 */
	public static function goHome(): string {
		return "home";
	}
}