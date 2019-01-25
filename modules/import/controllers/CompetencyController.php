<?php
declare(strict_types = 1);

namespace app\modules\import\controllers;

use app\models\core\WigetableController;
use Throwable;
use Yii;
use yii\web\ErrorAction;

/**
 * Class ImportController
 * @package app\controllers\admin
 */
class CompetencyController extends WigetableController {
	public $menuCaption = "<i class='fa fa-file-import'></i>Импорт компетенций";
	public $menuIcon = "/img/admin/import.png";
	public $disabled = false;
	public $orderWeight = 7;
	public $defaultRoute = 'import/competency';

	/**
	 * {@inheritDoc}
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
	 * @throws Throwable
	 */
	public function actionUpload():string {
//		$model = new ImportFos();
		if (Yii::$app->request->isPost && null !== $fileName = $model->uploadFile()) {
			$domain = time();
//			$model::Import($fileName, $domain);
//			$this->redirect(['index', 'domain' => $domain]);
		}

		return $this->render('upload', [
			'model' => null
		]);
	}
}
