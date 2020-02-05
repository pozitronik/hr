<?php
declare(strict_types = 1);

namespace app\modules\graph\models;

use app\modules\targets\models\Targets;
use pozitronik\helpers\ArrayHelper;
use Throwable;

/**
 * Класс построения графа целей
 * @package app\modules\graph\models
 *
 * @property int $upDepth -- глубина построения дерева вверх. 0 - только текущий уровень, отрицательное значение - нет ограничения
 * @property int $downDepth -- глубина построения дерева вниз. 0 - только текущий уровень, отрицательное значение - нет ограничения
 * @property TargetNode[] $nodes
 * @property TargetEdge[] $edges
 */
class TargetGraph extends Graph {
	/**
	 * {@inheritDoc}
	 */
	public function __construct(?Targets $target = null, $config = []) {
		parent::__construct($config);
		if (null !== $target) $this->buildGraph($target);
	}

	/**
	 * Строит двунаправленный граф для указанной цели
	 * @param Targets $target
	 * @throws Throwable
	 */
	public function buildGraph(Targets $target):void {
		$processedStack[$target->id] = true;
		$this->nodes[] = new TargetNode($target);
		$this->buildGraphUp($target, $processedStack);
		$this->buildGraphDown($target, $processedStack);
	}

	/**
	 * Строит граф вниз от указанной цели
	 * @param Targets $target
	 * @param array $processedStack -- массив обработанных целей для предотвращения зацикливания
	 * @param int $currentDepth
	 * @throws Throwable
	 */
	public function buildGraphDown(Targets $target, array &$processedStack = [], int &$currentDepth = 0):void {
		if ($this->downDepth < 0 || $currentDepth < $this->downDepth) {
			$currentDepth++;
			$processedStack[$target->id] = true;
			/** @var Targets $childTarget */
			foreach ((array)$target->relChildTargets as $childTarget) {
				if (false === ArrayHelper::getValue($processedStack, $childTarget->id, false)) {
					$this->nodes[] = new TargetNode($childTarget, ['y' => $currentDepth]);//позиционирование по y может использоваться при серверном расчёте координат, но его можно игнорировать при клиентском расчёте
					$processedStack[$childTarget->id] = true;
					$this->buildGraphDown($childTarget, $processedStack, $currentDepth);
				}

				$edge = new TargetEdge($target, $childTarget);
				if (!in_array($edge->id, ArrayHelper::getColumn($this->edges, "id"))) {
					$this->edges[] = $edge;
				}

			}
			$currentDepth--;
		}
	}

	/**
	 * Строит граф вверх от указанной цели
	 * @param Targets $target
	 * @param array $processedStack -- массив обработанных целей для предотвращения зацикливания
	 * @param int $currentDepth
	 * @throws Throwable
	 */
	public function buildGraphUp(Targets $target, array &$processedStack = [], int &$currentDepth = 0):void {
		if ($this->upDepth < 0 || $currentDepth < $this->upDepth) {
			$processedStack[$target->id] = true;
			$currentDepth++;
			if (null !== $target->relParentTarget) {
				if (false === ArrayHelper::getValue($processedStack, $target->relParentTarget->id, false)) {
					$this->nodes[] = new TargetNode($target->relParentTarget, ['y' => 1 * $currentDepth]);
					$processedStack[$target->relParentTarget->id] = true;
					$this->buildGraphUp($target->relParentTarget, $processedStack, $currentDepth);

				}
				$edge = new TargetEdge($target->relParentTarget, $target);
				if (!in_array($edge->id, ArrayHelper::getColumn($this->edges, "id"))) {
					$this->edges[] = $edge;
				}
			}

			$currentDepth--;
		}
	}

	/**
	 * @param mixed $upDepth
	 */
	public function setUpDepth($upDepth):void {
		$this->upDepth = $upDepth;
	}

	/**
	 * @param mixed $downDepth
	 */
	public function setDownDepth($downDepth):void {
		$this->downDepth = $downDepth;
	}

	/**
	 * Применяет набор позиций к текущим нодам
	 * @param array $positions -- позиции в формате [nodeId => [x,y]]
	 */
	public function applyNodesPositions(array $positions = []):void {
		foreach ($positions as $nodeId => $position) {
			if (false !== ($key = array_search($nodeId, array_column($this->nodes, 'id')))) {
				/** @var integer $key */
				$this->nodes[$key]->x = $position['x'];
				$this->nodes[$key]->y = $position['y'];
			}

		}
	}

}