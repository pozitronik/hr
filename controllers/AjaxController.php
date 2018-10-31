<?php
declare(strict_types = 1);

namespace app\controllers;

use app\helpers\ArrayHelper;
use app\models\prototypes\PrototypeNodeData;
use app\models\user\CurrentUser;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\web\Controller;

/**
 * Class AjaxController
 * Все внутренние аяксовые методы.
 * Все экшоны должны отдавать только массивы значений, формат, возможно, определю позже.
 * Каждый метод должен быть прокомментирован: что и откуда к нему лезет.
 *
 * @package app\controllers
 */
class AjaxController extends Controller {

	public const RESULT_OK = 0;/*Отработано*/
	public const RESULT_ERROR = 1;/*Ошибка*/
	public const RESULT_POSTPONED = 2;/*На будущее*/

	/**
	 * @inheritdoc
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
				'denyCallback' => function() {
					return ['success' => false];
				},
				'rules' => [
					[
						'allow' => true, //Yii::$app->request->isAjax, /*&& Yii::$app->user->identity,*/
						'actions' => [
							'groups-tree-save-node-position'
						],
						'roles' => ['@', '?']
					]
				]
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function beforeAction($action):bool {
		$this->enableCsrfValidation = false;//todo
		return parent::beforeAction($action);
	}

	/**
	 * Сохраняет позицию ноды в координатной сетке
	 * Сохранение производится для текущего пользователя, если он залогинен. Если нет - для браузерного юзер-фингерпринта.
	 * @return array
	 * @throws Throwable
	 */
	public function actionGroupsTreeSaveNodePosition():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$nodeData = new PrototypeNodeData();
		if ($nodeData->load(Yii::$app->request->post(), '')) {
			$user = CurrentUser::User();

			$user->options->nodePositions = ArrayHelper::merge_recursive($user->options->nodePositions, [
				$nodeData->groupId => [
					$nodeData->nodeId => [
						'x' => $nodeData->x,
						'y' => $nodeData->y
					]
				]
			]);
			return ['result' => self::RESULT_OK];
		}

		return [
			'result' => self::RESULT_ERROR,
			'errors' => $nodeData->errors

		];

	}

}
