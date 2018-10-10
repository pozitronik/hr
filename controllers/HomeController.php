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
		return $this->render('index',[
			'model' => CurrentUser::User()
		]);
	}

	/**
	 * Пытаемся загенерить матрицу ресурсов.
	 * Пока, конечно, тупо рисуем
	 * @return string
	 * @throws Throwable
	 */
	public function actionMatrix(){
		return $this->render('matrix',[
			'model' => CurrentUser::User()
		]);
	}

}
