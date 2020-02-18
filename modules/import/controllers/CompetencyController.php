<?php
declare(strict_types = 1);

namespace app\modules\import\controllers;

use app\models\core\controller\WigetableController;
use app\modules\import\models\competency\ImportCompetency;
use Throwable;
use Yii;
use yii\web\Response;

/**
 * Class ImportController
 * @package app\controllers\admin
 */
class CompetencyController extends WigetableController {
	public $menuCaption = "<i class='fa fa-file-import'></i>Импорт компетенций";
	public $menuIcon = "/img/admin/import.png";
	public $menuDisabled = false;
	public $orderWeight = 7;
	public $defaultRoute = 'import/competency';

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
		$model = new ImportCompetency();
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
	 * @throws Throwable
	 */
	public function actionImport() {
		$model = new ImportCompetency();
		$errors = [];
		return $model->Import($errors)?$this->render('done', [
			'messages' => $errors
		]):$this->refresh();

	}
}
