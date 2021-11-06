<?php
declare(strict_types = 1);

namespace app\modules\graph\controllers;

use app\models\core\controllers\WigetableController;
use app\models\user\CurrentUser;
use app\components\pozitronik\helpers\ArrayHelper;
use Throwable;

/**
 * Class GraphController
 * @package app\modules\graph\controllers
 */
class GraphController extends WigetableController {
	public $menuCaption = "<i class='fa fa-tree'></i>Структура";
	public $menuIcon = "/img/admin/graph.png";
	public $orderWeight = 2;
	public $defaultRoute = 'graph/graph';
	public $defaultAction = 'user';

	/**
	 * @param int $id -- id группы
	 * @return string
	 * @throws Throwable
	 */
	public function actionGroup(int $id):string {
		$positionConfigurations = ['default' => 'default'];
		/** @var array $groupMapConfigurations */
		if (false !== $groupMapConfigurations = ArrayHelper::getValue(CurrentUser::User()->options->nodePositionsConfig, $id, false)) {
			foreach ($groupMapConfigurations as $name => $nodes) {
				$positionConfigurations[$name] = $name;
			}
		}

		return $this->render('group', [
			'id' => $id,
			'currentConfiguration' => 'default',
			'positionConfigurations' => $positionConfigurations
		]);
	}

	/**
	 * @param null|int $id -- id пользователя
	 * @return string
	 * @throws Throwable
	 */
	public function actionUser(?int $id = null):string {
		return $this->render('user', [
			'id' => $id??$id = CurrentUser::Id(),
			'currentConfiguration' => 'default',
			'positionConfigurations' => ['default' => 'default']
		]);
	}

	/**
	 * @param int $id
	 * @return string
	 */
	public function actionTarget(int $id):string {
		return $this->render('target', [
			'id' => $id,
			'currentConfiguration' => 'default',
			'positionConfigurations' => ['default' => 'default']
		]);
	}

}