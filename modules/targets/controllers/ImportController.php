<?php
declare(strict_types = 1);

namespace app\modules\targets\controllers;

use app\models\core\WigetableController;
use app\modules\import\models\fos\ImportException;
use app\modules\targets\models\ImportTargets;
use Throwable;
use Yii;
use yii\web\Response;

/**
 * Class ImportController
 * @package app\modules\import\controllers
 */
class ImportController extends WigetableController {
	public $menuCaption = "<i class='fa fa-file-import'></i>Импорт целей";
	public $menuIcon = "/img/admin/import.png";
	public $menuDisabled = false;
	public $orderWeight = 7;
	public $defaultRoute = 'targets/import';

	/**
	 * @param int|null $domain
	 * @return string|Response
	 */
	public function actionIndex(?int $domain = null) {
		if (null === $domain) return $this->redirect(['upload']);

		return $this->render('index');
	}

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function actionUpload():string {
		$model = new ImportTargets();
		if (Yii::$app->request->isPost && null !== $fileName = $model->uploadFile()) {
			$domain = time();
			$model->Decompose($fileName, $domain);
			$this->redirect(['import', 'domain' => $domain]);
		}

		return $this->render('upload', [
			'model' => $model
		]);
	}

	/**
	 * @return string|Response
	 * @throws ImportException
	 * @throws Throwable
	 */
	public function actionImport() {
		$model = new ImportTargets();
		$errors = [];
		return $model->Import($errors)?$this->render('done', [
			'messages' => $errors
		]):$this->refresh();

	}
}
