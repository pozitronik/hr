<?php
declare(strict_types = 1);

namespace app\modules\graph\controllers;

use app\models\prototypes\NodesPositionsConfig;
use app\modules\graph\models\GraphNode;
use app\modules\graph\models\GroupGraph;
use app\modules\graph\models\GroupNode;
use pozitronik\helpers\ArrayHelper;
use app\models\core\ajax\BaseAjaxController;
use app\models\relations\RelUsersGroups;
use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\users\models\Users;
use app\modules\users\models\UsersSearch;
use Throwable;
use Yii;

/**
 * Class AjaxController
 * @package app\modules\groups\controllers
 */
class GroupsController extends BaseAjaxController {

	/**
	 * Возвращает ноду указанной группы
	 * @param int $id -- id группы
	 * @return array
	 * Не нужно, просто запрашиваем граф без глубин
	 */
	public function actionNode(int $id):array {
		if (null === $group = Groups::findModel($id)) {
			return $this->answer->addError('group', 'Not found');
		}
		$node = new GroupNode($group);
		return $node->toArray();
	}

	/**
	 * Отдаёт JSON с деревом графа для группы
	 * @param int $id -- id группы
	 * @param int $up -- глубина построения дерева вверх
	 * @param int $down -- глубина построения дерева вниз
	 * @return array
	 */
	public function actionGraph(int $id, int $up = 0, int $down = -1):array {
		if (null === $group = Groups::findModel($id)) {
			return $this->answer->addError('group', 'Not found');
		}
		$graph = new GroupGraph($group, ['upDepth' => $up, 'downDepth' => $down]);
		return $graph->toArray();
	}

	/**
	 * @param string $configName -- имя конфигурации нод
	 * @return array
	 */
	public function actionLoadPositions(string $configName = 'default'):array {

	}

	/*Экшены, которые потом выделить в сабмодуль графов!*/

	/**
	 * Отдаёт JSON с деревом графа для группы $is
	 * @param int $id -- id группы
	 * @param string $configName -- имя конфигурации
	 * @return array
	 * @throws Throwable
	 */
	public function actionGroupsTree(int $id, string $configName = 'default'):array {
		if (null === $user = CurrentUser::User()) return $this->answer->addError('user', 'Unauthorized');
		if (null === $group = Groups::findModel($id)) {
			return $this->answer->addError('group', 'Not found');
		}

		$nodes = [];
		$edges = [];
		$group->getGraph($nodes, $edges);
		$group->roundGraph($nodes);
		$groupMapConfigurations = ArrayHelper::getValue($user->options->nodePositionsConfig, $id, []);
		if (false !== $namedConfiguration = ArrayHelper::getValue($groupMapConfigurations, $configName, false)) {
			$group->applyNodesPositions($nodes, $namedConfiguration);
		}

//		switch ($restorePositions) {
//			default:
//			case 0:
//				$group->applyNodesPositions($nodes, ArrayHelper::getValue($user->options->nodePositionsConfig, $id, []));
//			break;
//			case 1:
//				$newPositions = $user->options->nodePositionsConfig;
//				unset($newPositions[$id]);
//				$user->options->nodePositionsConfig = $newPositions;
//			break;
//			case 2:
//				//do nothing
//			break;
//		}
		/*sigma.js требует выдачи данных в таком формате, пожтому answer не используем*/
		return compact('nodes', 'edges');
	}

	/**
	 * Сохраняет позицию ноды в координатной сетке
	 * Сохранение производится для текущего пользователя, если он залогинен. Если нет - для браузерного юзер-фингерпринта.
	 * @return array
	 * @throws Throwable
	 */
	public function actionGroupsTreeSaveNodePosition():array {
		//todo
	}

	/**
	 * Сохраянет позиции нод переданных массивом
	 * @return array
	 * @throws Throwable
	 */
	public function actionGroupsTreeSaveNodesPositions():array {
		if (null === $user = CurrentUser::User()) return $this->answer->addError('user', 'Unauthorized');//это должно разруливаться в behaviors()
		if (false !== (($nodes = Yii::$app->request->post('nodes', false)) && ($groupId = Yii::$app->request->post('groupId', false))) && ($configName = Yii::$app->request->post('name', false))) {
			$nodes = json_decode($nodes, true);
			$currentNodesPositions = $user->options->nodePositionsConfig;

			$currentPositionsConfig = new NodesPositionsConfig([
				'name' => $configName,
				'groupId' => $groupId
			]);

			$currentPositionsConfig->loadNodes($nodes);

			if ($currentPositionsConfig->hasErrors()) {
				return $this->answer->addErrors($currentPositionsConfig->errors);
			}

			$currentNodesPositions = ArrayHelper::merge_recursive($currentNodesPositions, $currentPositionsConfig->asArray());

			$user->options->nodePositionsConfig = $currentNodesPositions;
			return $this->answer->answer;
		}
		return $this->answer->addError('nodes', 'Can\'t load data');
	}

	/**
	 * Удаляет конфигурацию нод
	 * @return array
	 * @throws Throwable
	 */
	public function actionGroupsTreeDeleteNodesPositions():array {
		if (null === $user = CurrentUser::User()) return $this->answer->addError('user', 'Unauthorized');
		if (false !== $groupId = Yii::$app->request->post('groupId', false) && ($configName = Yii::$app->request->post('name', false))) {

			$userConfig = $user->options->nodePositionsConfig;
			/** @var string $groupId */
			unset($userConfig[$groupId][$configName]);

			$user->options->nodePositionsConfig = $userConfig;
			return $this->answer->answer;
		}
		return $this->answer->addError('nodes', 'Can\'t load data');
	}

	/**
	 * Генерит и отдаёт вьюшеньку с инфой о группе
	 */
	public function actionGetGroupInfo():array {
		if (null === ($group = Groups::findModel(Yii::$app->request->post('groupid')))) {
			return $this->answer->addError('group', 'Not found');
		}
		$this->answer->content = $this->renderPartial('get-group-info', [
			'group' => $group
		]);
		return $this->answer->answer;
	}

	/**
	 * AJAX user search
	 * @return array
	 */
	public function actionUsersSearch():array {
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
		$this->answer->count = $dataProvider->totalCount;
		$this->answer->items = $result;
		return $this->answer->answer;
	}

	/**
	 * Поиск группы в Select2
	 *
	 * @param string|null $term Строка поиска
	 * @param int $page Номер страницы (не поддерживается, задел на быдущее)
	 * @param int|null $user Пользователь ИСКЛЮЧАЕМЫЙ из поиска
	 * @return array
	 * @throws Throwable
	 */
	public function actionGroupSearch(?string $term = null, ?int $page = 0, ?int $user = null):array {
		$out = ['results' => ['id' => '', 'text' => '']];
		$results = [];
		if (null !== $term) {
			/** @var Groups[] $groups */
			$groups = Groups::find()->distinct()/*->select(['sys_groups.id', 'sys_groups.name as text'])*/
			->where(['like', 'sys_groups.name', $term])->andWhere(['not', ['sys_groups.id' => RelUsersGroups::find()->select('group_id')->where(['user_id' => $user])]])->offset(20 * $page)->limit(20)->all();
			foreach ($groups as $group) {
				$results[] = [
					'id' => $group->id,
					'text' => $group->name,
					'logo' => $group->logo,
					'typename' => ArrayHelper::getValue($group->relGroupTypes, 'name'),
					'typecolor' => ArrayHelper::getValue($group->relGroupTypes, 'color')
				];
			}
			$out['results'] = $results;
		}
		return $out;
	}
}