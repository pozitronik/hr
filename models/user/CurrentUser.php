<?php
declare(strict_types = 1);

namespace app\models\user;

use app\models\users\Users;
use Throwable;
use Yii;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

/**
 * Class CurrentUser
 * Такая себе предварительная обёртка, потом из неё вырастет нормальный враппер над всей фигнёй
 * @package app\models
 *
 */
class CurrentUser extends User {

	/**
	 * Отправляет на домашнюю страницу
	 */
	public static function goHome():Response {
		return Yii::$app->response->redirect(["home/index"]);
	}

	/**
	 * @return int|null
	 */
	public static function Id():?int {
		return Yii::$app->user->id;
	}

	/**
	 * @return bool
	 */
	public static function isGuest():bool {
		return Yii::$app->user->isGuest;
	}

	/**
	 * @return Users|false
	 * @throws Throwable
	 */
	public static function User() {
		return Users::findModel(self::Id(), new UnauthorizedHttpException('Пользователь не авторизован'));
	}
}