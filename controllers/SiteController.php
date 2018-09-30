<?php

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
	public function actionLogin(){
		return $this->render('login',[
			'login' => new LoginForm()
		]);
	}

	/**
	 * @return string
	 */
	public function actionIndex(){
		return $this->render('index');
	}

}
