<?php
declare(strict_types=1);

namespace app\controllers;

use app\models\user\CurrentUser;
use app\models\site\LoginForm;
use Yii;
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
	 * @return string
	 */
	public function actionLogin(): string {

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->doLogin()) {
			return CurrentUser::goHome();

		}
		return $this->render('login',[
			'login' => $model
		]);
	}

	/**
	 * @return string
	 */
	public function actionIndex(): string {
		return $this->render('index');
	}

}
