<?php
declare(strict_types = 1);

namespace app\modules\import\controllers;

use app\models\core\WigetableController;
use app\modules\import\models\ImportException;
use app\modules\import\models\fos\ImportFos;
use app\modules\import\models\fos\ImportFosDecomposed;
use app\modules\import\models\fos\ImportFosDecomposedSearch;
use app\modules\import\models\fos\ImportFosSearch;
use Throwable;
use Yii;
use yii\web\Response;

/**
 * Class FosController
 * @package app\modules\import\controllers
 */
class FosController extends WigetableController {
	public $menuCaption = "<i class='fa fa-file-import'></i>Импорт структуры SAP";
	public $menuIcon = "/img/admin/import.png";
	public $menuDisabled = false;
	public $orderWeight = 6;
	public $defaultRoute = 'import/fos';

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function actionUpload():string {
		$model = new ImportFos();
		if (Yii::$app->request->isPost && null !== $fileName = $model->uploadFile()) {
			$domain = time();
			$model::Import($fileName, $domain);
			$this->redirect(['index', 'domain' => $domain]);
		}

		return $this->render('upload', [
			'model' => $model
		]);
	}

	/**
	 * @param int|null $domain
	 * @return string|Response
	 */
	public function actionIndex(?int $domain = null) {
		if (null === $domain) return $this->redirect(['upload']);
		$params = Yii::$app->request->queryParams;
		$searchModel = new ImportFosSearch();

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params, $domain),
			'domain' => $domain
		]);
	}

	/**
	 * @param int|null $domain
	 * @param int $step
	 * @return string|Response
	 * @throws ImportException
	 */
	public function actionDecompose(?int $domain = null, int $step = ImportFos::STEP_REFERENCES) {
		if (null === $domain) return $this->redirect(['upload']);

		$messages = [];
		$step = ImportFos::Decompose($domain, $step, $messages);
		if ([] === $messages && $step !== ImportFos::LAST_STEP) {//если нет ошибок, сразу переходим к следующему шагу
			return $this->redirect(['decompose',
				'domain' => $domain,
				'messages' => $messages,
				'step' => $step + 1
			]);
		}
		return $this->render('decompose', compact('step', 'messages', 'domain'));
	}

	/**
	 * @param int|null $domain
	 * @return string|Response
	 */
	public function actionResult(?int $domain = null) {
		if (null === $domain) return $this->redirect(['upload']);
		$params = Yii::$app->request->queryParams;
		$searchModel = new ImportFosDecomposedSearch();
		return $this->render('result', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params, $domain),
			'domain' => $domain
		]);
	}

	/**
	 * @param int|null $domain
	 * @param int $step
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionImport(?int $domain = null, int $step = ImportFosDecomposed::STEP_GROUPS) {
		$cachedErrorsName = "ImportErrors".($domain??'');
		if (false === $errors = Yii::$app->cache->get($cachedErrorsName)) $errors = [];
		if (ImportFosDecomposed::LAST_STEP === $step) {
			return $this->render('import', compact('step', 'domain', 'errors'));
		}

		$importResult = ImportFosDecomposed::Import($step, $errors);
		Yii::$app->cache->set($cachedErrorsName, $errors);
		return $this->redirect(['import',
			'domain' => $domain,
			'step' => $importResult?$step + 1:$step
		]);

	}
}
