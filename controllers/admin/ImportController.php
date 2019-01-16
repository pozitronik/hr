<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\core\WigetableController;
use app\models\imports\ImportFos;
use Yii;
use yii\web\ErrorAction;

/**
 * Class ImportController
 * @package app\controllers\admin
 */
class ImportController extends WigetableController {
	public $menuCaption = "<i class='fa fa-file-import'></i>Импорт";
	public $menuIcon = "/img/admin/import.png";
	public $disabled = false;
	public $orderWeight = 6;

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
	 */
	public function actionIndex():string {
		$model = new ImportFos();
		if (Yii::$app->request->isPost) {
			if (null !== $fileName = $model->uploadFile()) {
				$model::Import($fileName, time());
			}
		}

		return $this->render('index',[
			'model' => $model
		]);
	}

}
