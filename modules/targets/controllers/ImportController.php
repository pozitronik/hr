<?php
declare(strict_types = 1);

namespace app\modules\targets\controllers;

use app\models\core\WigetableController;
use app\modules\targets\models\import\ImportTargets;
use app\modules\targets\models\import\ImportTargetsSearch;
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
	 * @param int $step
	 * @return string|Response
	 */
	public function actionDecompose(?int $domain = null, int $step = ImportTargets::STEP_REFERENCES) {
		if (null === $domain) return $this->redirect(['upload']);

		$messages = [];
		$step = ImportTargets::Decompose($domain, $step, $messages);
		if ([] === $messages && $step !== ImportTargets::LAST_STEP) {//если нет ошибок, сразу переходим к следующему шагу
			return $this->redirect(['decompose',
				'domain' => $domain,
				'messages' => $messages,
				'step' => $step + 1
			]);
		}
		return $this->render('decompose', compact('step', 'messages', 'domain'));
	}
}
