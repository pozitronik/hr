<?php
declare(strict_types=1);

namespace app\controllers;

use app\models\site\LoginForm;
use yii\web\Controller;
use yii\web\ErrorAction;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller {

	public $defaultAction = 'index';


	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error' => [
				'class' => ErrorAction::class
			]
		];
	}

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
