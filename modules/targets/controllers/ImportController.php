<?php
declare(strict_types = 1);

namespace app\modules\targets\controllers;

use app\models\core\controller\WigetableController;
use app\modules\targets\models\import\ImportTargets;
use app\modules\targets\models\import\ImportTargetsSearch;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
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

		$params = Yii::$app->request->queryParams;
		$searchModel = new ImportTargetsSearch();

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params, $domain),
			'domain' => $domain
		]);
	}

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function actionUpload():string {
		$model = new ImportTargets();
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
	 * @throws Exception
	 * @throws InvalidConfigException
	 */
	public function actionDecompose(?int $domain = null) {
		if (null === $domain) return $this->redirect(['upload']);

		$messages = [];
		ImportTargets::Decompose($domain, $messages);
		if ([] === $messages) {//если нет ошибок, сразу переходим к следующему шагу
			return $this->redirect(['db-import',
				'domain' => $domain,
				'messages' => $messages,
			]);
		}
		return $this->render('decompose', compact('messages', 'domain'));
	}

	/**
	 * @param int|null $domain
	 * @param int $step
	 * @return string|Response
	 * @throws Throwable
	 * @throws NotFoundHttpException
	 */
	public function actionDbImport(?int $domain = null, int $step = ImportTargets::STEP_GROUPS) {
		$cachedErrorsName = "ImportErrors".($domain??'');
		if (false === $errors = Yii::$app->cache->get($cachedErrorsName)) $errors = [];
		if (ImportTargets::LAST_STEP === $step) {
			return $this->render('db-import', compact('step', 'domain', 'errors'));
		}

		$importResult = ImportTargets::ImportToDB($step);
		Yii::$app->cache->set($cachedErrorsName, $errors);
		return $this->redirect(['db-import',
			'domain' => $domain,
			'step' => $importResult?$step + 1:$step
		]);

	}
}
