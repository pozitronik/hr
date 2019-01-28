<?php
declare(strict_types = 1);

namespace app\modules\import\controllers;

use app\models\core\WigetableController;
use app\modules\import\models\competency\ImportCompetency;
use Throwable;
use Yii;
use yii\web\ErrorAction;
use yii\web\Response;

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
			$model->Decompose($fileName, $domain);//todo: поскольку процесс долгий, предусмотреть таймауты с рефрешем: обработал N записей, остановился, повторил с тем же файлом, пока файл не кончился.
			$this->redirect(['import', 'domain' => $domain]);
		}

		return $this->render('upload', [
			'model' => $model
		]);
	}

	/**
	 * @return string
	 */
	public function actionImport():string {
		$model = new ImportCompetency();
		$errors = $model->Import();
		return $this->render('done', [
			'messages' => $errors
		]);
	}
}
