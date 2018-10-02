<?php
declare(strict_types=1);

namespace app\models\user;

use Yii;

/**
 * Class CurrentUser
 * Такая себе предварительная обёртка, потом из неё вырастет нормальный враппер над всей фигнёй
 * @package app\models
 *
 */
class CurrentUser extends User {

	/**
	 * Отправляет на домашнюю страницу
	 * @return string
	 */
	public static function goHome(): string {
		return "home";
	}

	/**
	 * @return int|null
	 */
	public static function Id(): ?int {
		return Yii::$app->user->id;
	}
}