<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\user\CurrentUser;
use Throwable;
use yii\base\Response;
use yii\web\Controller;

/**
 * Class HomeController
 * @package app\controllers
 */
class HomeController extends Controller {

	/**
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionIndex() {
		if (null === $user = CurrentUser::User()) return $this->redirect(['site/login']);
		return $this->render('index',[
			'model' => $user
		]);
	}

	/**
	 * Пытаемся загенерить матрицу ресурсов.
	 * Пока, конечно, тупо рисуем
	 * @return string
	 * @throws Throwable
	 */
	public function actionMatrix():string {
		return $this->render('matrix');
	}

}
