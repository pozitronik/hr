<?php
declare(strict_types = 1);

namespace app\modules\import\controllers;

use app\models\core\controllers\WigetableController;
use app\modules\import\models\beeline\ImportBeeline;
use app\modules\import\models\beeline\ImportBeelineDecomposed;
use app\modules\import\models\beeline\ImportBeelineDecomposedSearch;
use app\modules\import\models\beeline\ImportBeelineSearch;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\web\Response;

/**
 * Class BeelineController
 */
class BeelineController extends WigetableController {
	public $menuCaption = "<i class='fa fa-file-import'></i>Импорт структуры Beeline";
	public $menuIcon = "/img/admin/import.png";
	public $menuDisabled = false;
	public $orderWeight = 6;
	public $defaultRoute = 'import/beeline';

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function actionUpload():string {
		$model = new ImportBeeline();
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
		$searchModel = new ImportBeelineSearch();

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
	 * @throws Exception
	 * @throws InvalidConfigException
	 */
	public function actionDecompose(?int $domain = null, int $step = ImportBeeline::STEP_REFERENCES) {
		if (null === $domain) return $this->redirect(['upload']);

		$messages = [];
		$step = ImportBeeline::Decompose($domain, $step, $messages);
		if ([] === $messages && $step !== ImportBeeline::LAST_STEP) {//если нет ошибок, сразу переходим к следующему шагу
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
		$searchModel = new ImportBeelineDecomposedSearch();
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
	public function actionImport(?int $domain = null, int $step = ImportBeelineDecomposed::STEP_GROUPS) {
		$cachedErrorsName = "ImportErrors".($domain??'');
		if (false === $errors = Yii::$app->cache->get($cachedErrorsName)) $errors = [];
		if (ImportBeelineDecomposed::LAST_STEP === $step) {
			return $this->render('import', compact('step', 'domain', 'errors'));
		}

		$importResult = ImportBeelineDecomposed::Import($step, $errors);
		Yii::$app->cache->set($cachedErrorsName, $errors);
		return $this->redirect(['import',
			'domain' => $domain,
			'step' => $importResult?$step + 1:$step
		]);

	}
}
