<?php
declare(strict_types = 1);

namespace app\modules\groups\controllers;

use app\helpers\ArrayHelper;
use app\models\core\ajax\AjaxAnswer;
use app\models\core\ajax\BaseAjaxController;
use app\models\prototypes\PrototypeNodeData;
use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\users\models\Users;
use app\modules\users\models\UsersSearch;
use Throwable;
use Yii;
use yii\web\Response;

/**
 * Class AjaxController
 * @package app\modules\groups\controllers
 */
class AjaxController extends BaseAjaxController {

	/*Экшены, которые потом выделить в сабмодуль графов!*/

	/**
	 * Отдаёт JSON с деревом графа для группы $is
	 * @param int $id
	 * @param int $restorePositions 0: use saved nodes positions, 1 - use original positions and reset saved positions, 2 - just use original
	 * @return array
	 * @throws Throwable
	 */
	public function actionGroupsTree(int $id, int $restorePositions = 0):array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$answer = new AjaxAnswer();
		if (null === $user = CurrentUser::User()) return $answer->addError('user', 'Unauthorized');
		if (null === $group = Groups::findModel($id)) {
			return $answer->addError('group', 'Not found');
		}

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
		$answer = new AjaxAnswer();
		$nodeData = new PrototypeNodeData();
		if (null === $user = CurrentUser::User()) return $answer->addError('user', 'Unauthorized');
		if ($nodeData->load(Yii::$app->request->post(), '')) {
			$user->options->nodePositions = ArrayHelper::merge_recursive($user->options->nodePositions, [
				$nodeData->groupId => [
					$nodeData->nodeId => [
						'x' => $nodeData->x,
						'y' => $nodeData->y
					]
				]
			]);
			return $answer->answer;
		}
		return $answer->addErrors($nodeData->errors);
	}

	/**
	 * Сохраянет позиции нод переданных массивом
	 * @return array
	 * @throws Throwable
	 */
	public function actionGroupsTreeSaveNodesPositions():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$answer = new AjaxAnswer();
		if (null === $user = CurrentUser::User()) return $answer->addError('user', 'Unauthorized');//это должно разруливаться в behaviors()
		if (false !== (($nodes = Yii::$app->request->post('nodes', false)) && ($groupId = Yii::$app->request->post('groupId', false)))) {
			$nodes = json_decode($nodes, true);
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
					return $answer->addErrors($nodeData->errors);
				}
			}
			$user->options->nodePositions = $currentNodesPositions;
			return $answer->answer;
		}
		return $answer->addError('nodes', 'Can\'t load data');
	}

	/**
	 * Генерит и отдаёт вьюшеньку с инфой о группе
	 */
	public function actionGetGroupInfo():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$answer = new AjaxAnswer();
		if (null === ($group = Groups::findModel(Yii::$app->request->post('groupid')))) {
			return $answer->addError('group', 'Not found');
		}
		$answer->content = $this->renderPartial('get-group-info', [
			'group' => $group
		]);
		return $answer->answer;
	}

	/**
	 * AJAX user search
	 * @return array
	 */
	public function actionUsersSearch():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$answer = new AjaxAnswer();
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
		$answer->count = $dataProvider->totalCount;
		$answer->items = $result;
		return $answer->answer;

	}
}