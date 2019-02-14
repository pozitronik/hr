<?php
declare(strict_types = 1);

namespace app\controllers;

use app\helpers\ArrayHelper;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\groups\models\Groups;
use app\models\prototypes\PrototypeNodeData;
use app\models\relations\RelGroupsGroups;
use app\models\user\CurrentUser;
use app\modules\users\models\Bookmarks;
use app\modules\users\models\Users;
use app\modules\users\models\UsersSearch;
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
	 * Отдаёт JSON с деревом графа для группы $is
	 * @param int $id
	 * @param int $restorePositions 0: use saved nodes positions, 1 - use original positions and reset saved positions, 2 - just use original
	 * @return array
	 * @throws Throwable
	 */
	public function actionGroupsTree(int $id, int $restorePositions = 0):array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		if (null === $group = Groups::findModel($id)) {
			return [
				'result' => self::RESULT_ERROR,
				'errors' => [
					'group' => 'Not found'
				]
			];
		}
		if (null === $user = CurrentUser::User()) return ['result' => self::RESULT_ERROR, 'errors' => 'Unauthorized'];
		$nodes = [];
		$edges = [];
		$group->getGraph($nodes, $edges);
		$group->roundGraph($nodes);
		switch ($restorePositions) {
			default:
			case 0:
				$group->applyNodesPositions($nodes, ArrayHelper::getValue($user->options->nodePositions, $id, []));
			break;
			case 1:
				$newPositions = $user->options->nodePositions;
				unset($newPositions[$id]);
				$user->options->nodePositions = $newPositions;
			break;
			case 2:
				//do nothing
			break;
		}
		//todo: стандартизировать ответ
		return compact('nodes', 'edges');
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
			if (null === $user = CurrentUser::User()) return ['result' => self::RESULT_ERROR, 'errors' => 'Unauthorized'];
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

	/**
	 * Сохраянет позиции нод переданных массивом
	 * @return array
	 * @throws Throwable
	 */
	public function actionGroupsTreeSaveNodesPositions():array {
		Yii::$app->response->format = Response::FORMAT_JSON;

		if (false !== (($nodes = Yii::$app->request->post('nodes', false)) && ($groupId = Yii::$app->request->post('groupId', false)))) {
			$nodes = json_decode($nodes, true);
			if (null === $user = CurrentUser::User()) return ['result' => self::RESULT_ERROR, 'errors' => 'Unauthorized'];
			$currentNodesPositions = $user->options->nodePositions;

			/** @var array $nodes */
			foreach ($nodes as $node) {
				$nodeData = new PrototypeNodeData([
					'groupId' => $groupId
				]);
				if ($nodeData->load($node, '')) {


					$currentNodesPositions = ArrayHelper::merge_recursive($currentNodesPositions, [
						$nodeData->groupId => [
							$nodeData->nodeId => [
								'x' => $nodeData->x,
								'y' => $nodeData->y
							]
						]
					]);

				} else {
					return [
						'result' => self::RESULT_ERROR,
						'errors' => $nodeData->errors
					];
				}
			}
			$user->options->nodePositions = $currentNodesPositions;
			return ['result' => self::RESULT_OK];
		}
		return [
			'result' => self::RESULT_ERROR,
			'errors' => [
				'nodes' => 'Cant load data'
			]
		];
	}

	/**
	 * Генерит и отдаёт вьюшеньку с инфой о группе
	 */
	public function actionGetGroupInfo():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		if (null === ($group = Groups::findModel(Yii::$app->request->post('groupid')))) {
			return [
				'result' => self::RESULT_ERROR,
				'errors' => [
					'group' => 'Not found'
				]
			];
		}
		return [
			'result' => self::RESULT_OK,
			'content' => $this->renderPartial('get-group-info', [
				'group' => $group
			])
		];
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

	/**
	 * AJAX user search
	 * @return array
	 */
	public function actionUsersSearch():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$searchModel = new UsersSearch();
		$allowedGroups = [];
		//Проверяем доступы к списку юзеров
		$searchArray = [//Быстрый костыль для демо
			'UsersSearch' => Yii::$app->request->post()
		];
		$dataProvider = $searchModel->search($searchArray, $allowedGroups, false);
		$result = [];
		/** @var Users $model */
		foreach ($dataProvider->models as $model) {
			$result[] = [
				'username' => $model->username,
				'groups' => ArrayHelper::getColumn($model->relGroups, 'id')
			];
		}
		return [
			'result' => self::RESULT_OK,
			'count' => $dataProvider->totalCount,
			'items' => $result
		];

	}

	/**
	 * Добавляет закладку текущему пользователю
	 * @return array
	 * @throws Throwable
	 */
	public function actionUserAddBookmark():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$bookmark = new Bookmarks();
		if ($bookmark->load(Yii::$app->request->post(), '')) {
			if (null === $user = CurrentUser::User()) return ['result' => self::RESULT_ERROR, 'errors' => 'Unauthorized'];
			$bookmarks = $user->options->bookmarks;
			$bookmarks[] = $bookmark;
			$user->options->bookmarks = $bookmarks;
			return ['result' => self::RESULT_OK];
		}
		return [
			'result' => self::RESULT_ERROR,
			'errors' => $bookmark->errors
		];
	}

	/**
	 * Удаляет закладку
	 * @return array
	 * @throws Throwable
	 */
	public function actionUserRemoveBookmark():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		if (false !== $route = Yii::$app->request->post('route', false)) {
			if (null === $user = CurrentUser::User()) return ['result' => self::RESULT_ERROR, 'errors' => 'Unauthorized'];
			$bookmarks = $user->options->bookmarks;
			foreach ($bookmarks as $key => $value) {
				if ($route === $value->route) unset($bookmarks[$key]);
			}
			$user->options->bookmarks = $bookmarks;
			return ['result' => self::RESULT_OK];
		}
		return [
			'result' => self::RESULT_ERROR,
			'errors' => [
				'route' => 'not found'
			]
		];

	}

}
