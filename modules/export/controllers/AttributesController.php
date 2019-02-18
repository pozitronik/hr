<?php
declare(strict_types = 1);

namespace app\modules\export\controllers;

use app\models\core\WigetableController;
use app\modules\export\models\attributes\ExportAttributes;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * Class ImportController
 * @package app\controllers\admin
 */
class AttributesController extends WigetableController {
	public $menuCaption = "<i class='fa fa-file-export'></i>Экспорт атрибутов";
//	public $menuIcon = "/img/admin/import.png";
	public $disabled = false;
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
		ExportAttributes::UserExport($id);
		return $this->render('index');
	}
}
