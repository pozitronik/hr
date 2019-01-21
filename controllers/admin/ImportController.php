<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\core\WigetableController;
use app\models\imports\ImportFos;
use app\models\imports\ImportFosDecomposed;
use app\models\imports\ImportFosDecomposedSearch;
use app\models\imports\ImportFosSearch;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\ErrorAction;
use yii\web\Response;

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
	 * @throws InvalidConfigException
	 * @throws PhpSpreadsheetException
	 * @throws Exception
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
		return $this->render('decompose', [
			'step' => $step,
			'messages' => $messages,
			'domain' => $domain
		]);
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
	 * @return string|Response
	 */
	public function actionImport(?int $domain = null) {
		if (null === $domain) return $this->redirect(['upload']);
		ImportFosDecomposed::Import($domain);
		return $this->render('import', [
			'domain' => $domain
		]);
	}
}
