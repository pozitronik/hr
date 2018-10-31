<?php
declare(strict_types = 1);

namespace app\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\ErrorAction;

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
	public function behaviors() {
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
						'allow' => Yii::$app->request->isAjax && Yii::$app->user->identity,
						'actions' => [
							'groups-tree-save-node-position'
						],
						'roles' => ['@']
					],
				]
			]
		];
	}

	/**
	 * Сохраняет позицию ноды в координатной сетке
	 * Сохранение производится для текущего пользователя, если он залогинен. Если нет - для браузерного юзер-фингерпринта.
	 * @param int $group_id
	 * @return array
	 */
	public function actionGroupsTreeSaveNodePosition(int $group_id):array {
		$nodeData = new DynamicModel(['groupId', 'nodeId', 'x', 'y', 'userId']);
		$nodeData->addRule(['groupId', 'nodeId', 'userId'], 'integer');
		$nodeData->addRule(['x', 'y'], 'float');
		$nodeData->addRule(['groupId', 'nodeId', 'x', 'y'], 'required');
		$nodeData->groupId = $group_id;
		if ($nodeData->load(Yii::$app->request->post())) {
			return ['result' => self::RESULT_OK];
		} else {
			return [
				'result' => self::RESULT_ERROR,
				'errors' => $nodeData->errors

			];
		}

	}

}
