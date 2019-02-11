<?php
declare(strict_types = 1);

namespace app\models\user;

use app\modules\users\models\Users;
use Throwable;
use Yii;
use yii\web\Response;

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
		return Yii::$app->user->isGuest;// || null === Yii::$app->user->id;
	}

	/**
	 * @return Users|null
	 * @throws Throwable
	 */
	public static function User():?Users {
		return Users::findModel(self::Id()/*, new UnauthorizedHttpException('Пользователь не авторизован')*/);
	}
}