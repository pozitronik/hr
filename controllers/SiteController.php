<?php
declare(strict_types=1);

namespace app\controllers;

use yii\web\Controller;
use app\models\LoginForm;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller {

	public $defaultAction = 'login';

	/**
	 * @return string
	 */
	public function actionLogin(): string {
		return $this->render('login',[
			'login' => new LoginForm()
		]);
	}

	/**
	 * @return string
	 */
	public function actionIndex(): string {
		return $this->render('index');
	}

}
