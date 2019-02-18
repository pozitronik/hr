<?php
declare(strict_types = 1);

namespace app\modules\export\controllers;

use app\models\core\WigetableController;
use app\modules\export\models\attributes\ExportAttributes;
use app\modules\users\models\Users;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ImportController
 * @package app\controllers\admin
 */
class AttributesController extends WigetableController {
	public $menuCaption = "<i class='fa fa-file-export'></i>Экспорт атрибутов";
//	public $menuIcon = "/img/admin/import.png";
	public $disabled = true;
	public $orderWeight = 7;
	public $defaultRoute = 'export/competency';

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
	public function actionUser(int $id) {
		$this->layout = false;
		if (null === $user = Users::findModel($id, new NotFoundHttpException())) return null;
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$user->username.'.xlsx"');
		header('Cache-Control: max-age=0');
		ExportAttributes::UserExport($id);
//		readfile($filename);
		die;
//		return $this->render('index');
	}
}
