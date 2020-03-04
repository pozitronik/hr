<?php
declare(strict_types = 1);

namespace app\models\core\controllers;

use pozitronik\core\models\core_controller\ControllerTrait;
use pozitronik\core\traits\ModelExtended;
use app\modules\privileges\models\UserAccess;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * Базовая модель веб-контроллера приложения
 * Class CoreController
 * @package app\models\core
 */
class CoreController extends Controller {
	use ModelExtended;
	use ControllerTrait;

	/**
	 * {@inheritDoc}
	 */
	public function behaviors():array {
		return [
			[
				'class' => ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
					'application/xml' => Response::FORMAT_XML,
					'text/html' => Response::FORMAT_HTML
				]
			],
			'access' => [
				'class' => AccessControl::class,
				'rules' => UserAccess::getUserAccessRules($this)
			]
		];
	}

	/**
	 * @return array
	 */
	public function actions():array {
		return [
			'error' => [
				'class' => ErrorAction::class
			]
		];
	}

}