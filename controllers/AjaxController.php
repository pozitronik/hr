<?php
declare(strict_types = 1);

namespace app\controllers;

use app\modules\groups\models\Groups;
use app\models\relations\RelGroupsGroups;
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
class AjaxController extends Controller {//todo вынести экшены, относящиеся к модульному коду, в модули

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
						'allow' => /*Yii::$app->request->isAjax, */
							Yii::$app->user->identity,
						'actions' => [
							'groups-tree',
							'groups-tree-save-node-position',
							'groups-tree-save-nodes-positions',
							'get-group-info',
							'set-user-roles-in-group',
							'set-group-relation-type',
							'set-group-type',
							'users-search',
							'user-add-bookmark',
							'user-remove-bookmark',
							'attribute-get-properties',
							'attribute-get-property-condition'
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
	 * Принимает массив ролей пользователя, применяя их
	 * @return array
	 * @throws Throwable
	 */
	public function actionSetUserRolesInGroup():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$groupId = Yii::$app->request->post('groupId', false);
		$userId = Yii::$app->request->post('userId', false);
		if (!($groupId && $userId)) {
			return [
				'result' => self::RESULT_ERROR,
				'errors' => [
					'parameters' => 'Not enough parameters'
				]
			];
		}
		/** @var Groups $group */
		if (null === ($group = Groups::findModel($groupId))) {
			return [
				'result' => self::RESULT_ERROR,
				'errors' => [
					'group' => 'Not found'
				]
			];
		}

		$group->setRolesInGroup([$userId => Yii::$app->request->post('roles', [])]);
		return [
			'result' => self::RESULT_OK
		];

	}

	/**
	 * Принимает и применяет тип релейшена между двумя группами
	 * Предполагается, что релейшен уже существует
	 * @return array
	 * @throws Throwable
	 */
	public function actionSetGroupRelationType():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$parentGroupId = Yii::$app->request->post('parentGroupId', false);
		$childGroupId = Yii::$app->request->post('childGroupId', false);
		$relation = Yii::$app->request->post('relation', false);
		if (!($parentGroupId && $childGroupId)) {
			return [
				'result' => self::RESULT_ERROR,
				'errors' => [
					'parameters' => 'Not enough parameters'
				]
			];
		}

		/** @var Groups $group */
		if (false === ($groupsRelation = RelGroupsGroups::find()->where(['parent_id' => $parentGroupId, 'child_id' => $childGroupId])->one())) {
			return [
				'result' => self::RESULT_ERROR,
				'errors' => [
					'groupsRelation' => 'Not found'
				]
			];
		}
		$groupsRelation->setAndSaveAttribute('relation', $relation);
		return [
			'result' => self::RESULT_OK
		];

	}

	/**
	 * Принимает и применяет тип группы
	 * @return array
	 * @throws Throwable
	 */
	public function actionSetGroupType():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$groupId = Yii::$app->request->post('groupId', false);
		$type = Yii::$app->request->post('type', false);
		if (!$groupId) {
			return [
				'result' => self::RESULT_ERROR,
				'errors' => [
					'parameters' => 'Not enough parameters'
				]
			];
		}
		/** @var Groups $group */
		if (null === ($group = Groups::findModel($groupId))) {
			return [
				'result' => self::RESULT_ERROR,
				'errors' => [
					'group' => 'Not found'
				]
			];
		}

		$group->setAndSaveAttribute('type', $type);
		return [
			'result' => self::RESULT_OK
		];
	}





}
