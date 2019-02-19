<?php
declare(strict_types = 1);

namespace app\modules\export\controllers;

use app\models\core\WigetableController;
use app\modules\export\models\attributes\ExportAttributes;
use app\modules\users\models\Users;
use Throwable;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ImportController
 * @package app\controllers\admin
 */
class AttributesController extends WigetableController {
	public $menuCaption = "<i class='fa fa-file-export'></i>Экспорт атрибутов";
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
	 * Выдать экспорт атрибутов пользователя
	 * @param int $id
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionUser(int $id) {
		$this->layout = false;
		if (null === $user = Users::findModel($id, new NotFoundHttpException())) return null;
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$user->username.'.xlsx"');
		header('Cache-Control: max-age=0');
		ExportAttributes::UserExport($id);
		die;
	}
}
