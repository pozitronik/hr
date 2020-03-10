<?php
declare(strict_types = 1);

namespace app\modules\graph\controllers;

use app\modules\graph\models\TargetGraph;
use app\modules\targets\models\Targets;
use app\models\core\controllers\BaseAjaxController;
use Throwable;

/**
 * Class TargetsController
 * @package app\modules\graph\controllers
 */
class TargetsController extends BaseAjaxController {

	/**
	 * Отдаёт JSON с деревом графа для цели
	 * @param int $id -- id цели
	 * @param int $up -- глубина построения дерева вверх
	 * @param int $down -- глубина построения дерева вниз
	 * Для получения одной ноды спрашиваем с up = 0/down = 0
	 * @return array
	 * @throws Throwable
	 */
	public function actionGraph(int $id, int $up = -1, int $down = -1):array {
		if (null === $target = Targets::findModel($id)) {
			return $this->answer->addError('target', 'Not found');
		}
		$graph = new TargetGraph($target, ['upDepth' => $up, 'downDepth' => $down]);
		$graph->roundNodes();
		return $graph->toArray();
	}

}