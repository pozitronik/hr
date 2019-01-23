<?php
declare(strict_types = 1);

namespace app\modules\import\controllers;

use yii\web\Controller;

/**
 * Default controller for the `ImportFos` module
 */
class DefaultController extends Controller {
	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function actionIndex() {
		return $this->render('index');
	}
}
