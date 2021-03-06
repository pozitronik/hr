<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\user\CurrentUser;
use app\models\site\LoginForm;
use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\web\ErrorAction;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller {

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
	 * @return string|Response
	 */
	public function actionLogin() {
		$this->layout = 'login';
		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->doLogin()) {
			return CurrentUser::goHome();

		}
		return $this->render('login', [
			'login' => $model
		]);
	}

	/**
	 *
	 */
	public function actionLogout():void {
		Yii::$app->user->logout();
		$this->redirect('index');
	}

	/**
	 * @return Response
	 */
	public function actionIndex():Response {
		return CurrentUser::isGuest()?$this->redirect(['site/login']):CurrentUser::goHome();
	}

}
