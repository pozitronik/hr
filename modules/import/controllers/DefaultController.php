<?php
declare(strict_types = 1);

namespace app\modules\import\controllers;

use app\models\core\WigetableController;
use app\modules\import\models\ImportFos;
use app\modules\import\models\ImportFosDecomposed;
use app\modules\import\models\ImportFosDecomposedSearch;
use app\modules\import\models\ImportFosSearch;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * Class ImportController
 * @package app\controllers\admin
 */
class DefaultController extends WigetableController {
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
	 * @throws InvalidConfigException
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
	 */
	public function actionDecompose(?int $domain = null, int $step = 0) {
		if (null === $domain) return $this->redirect(['upload']);
		$messages = ImportFos::Decompose($domain, $step);
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
		if (null === $domain) return $this->redirect(['upload']);
		$step = ImportFosDecomposed::Import($step);
		return $this->render('import', compact('step', 'domain'));
	}
}
