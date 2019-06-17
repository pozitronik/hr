<?php
declare(strict_types = 1);

namespace app\modules\graph\controllers;

use app\models\prototypes\NodesPositionsConfig;
use app\modules\graph\models\GroupGraph;
use pozitronik\helpers\ArrayHelper;
use app\models\core\ajax\BaseAjaxController;
use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use Throwable;
use Yii;

/**
 * Class AjaxController
 * @package app\modules\groups\controllers
 */
class GroupsController extends BaseAjaxController {

	/**
	 * Отдаёт JSON с деревом графа для группы
	 * @param int $id -- id группы
	 * @param int $up -- глубина построения дерева вверх
	 * @param int $down -- глубина построения дерева вниз
	 * Для получения одной ноды спрашиваем с up = 0/down = 0
	 * @return array
	 * @throws Throwable
	 */
	public function actionGraph(int $id, int $up = 0, int $down = -1):array {
		if (null === $group = Groups::findModel($id)) {
			return $this->answer->addError('group', 'Not found');
		}
		$graph = new GroupGraph($group, ['upDepth' => $up, 'downDepth' => $down]);
		return $graph->toArray();
	}

	/**
	 * @param int $id -- id группы
	 * @param string $configName -- имя конфигурации нод
	 * @return array
	 * @throws Throwable
	 */
	public function actionLoadPositions(int $id, string $configName = 'default'):array {
		if (null === $user = CurrentUser::User()) return $this->answer->addError('user', 'Unauthorized');
		if (null === $group = Groups::findModel($id)) {
			return $this->answer->addError('group', 'Not found');
		}
		$groupMapConfigurations = ArrayHelper::getValue($user->options->nodePositionsConfig, $id, []);
		$graph = new GroupGraph($group);
		if (false !== $namedConfiguration = ArrayHelper::getValue($groupMapConfigurations, $configName, false)) {
			$graph->applyNodesPositions($namedConfiguration);
		}
		return $graph->toArray();
	}

	/**
	 * @return array
	 * @throws Throwable
	 */
	public function actionSavePositions():array {
		if (null === $user = CurrentUser::User()) return $this->answer->addError('user', 'Unauthorized');

		if (false !== (($nodes = Yii::$app->request->post('nodes', false)) && ($groupId = Yii::$app->request->post('id', false))) && ($configName = Yii::$app->request->post('configName', false))) {
			if (null === Groups::findModel($groupId)) {
				return $this->answer->addError('group', 'Not found');
			}
			if (false !== $nodes = Yii::$app->request->post('nodes', false)) {
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
		}

		return $this->answer->addError('nodes', 'Can\'t load data');
	}

	/**
	 * @param int $id
	 * @param string $configName
	 * @return array
	 * @throws Throwable
	 */
	public function actionDeletePositions(int $id, string $configName):array {
		if (null === $user = CurrentUser::User()) return $this->answer->addError('user', 'Unauthorized');
		if (null === Groups::findModel($id)) {
			return $this->answer->addError('group', 'Not found');
		}

		$userConfig = $user->options->nodePositionsConfig;
		/** @var string $groupId */
		unset($userConfig[$id][$configName]);

		$user->options->nodePositionsConfig = $userConfig;
		return $this->answer->answer;
	}

}