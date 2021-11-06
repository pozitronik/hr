<?php
declare(strict_types = 1);

namespace app\modules\graph\models;

use app\modules\groups\models\Groups;
use app\components\pozitronik\helpers\ArrayHelper;
use Throwable;

/**
 * Класс построения графа групп
 * Class GroupGraph
 * @package app\modules\graph\models
 *
 * @property int $upDepth -- глубина построения дерева вверх. 0 - только текущий уровень, отрицательное значение - нет ограничения
 * @property int $downDepth -- глубина построения дерева вниз. 0 - только текущий уровень, отрицательное значение - нет ограничения
 * @property GroupNode[] $nodes
 * @property GroupEdge[] $edges
 */
class GroupGraph extends Graph {
	/**
	 * {@inheritDoc}
	 */
	public function __construct(?Groups $group = null, $config = []) {
		parent::__construct($config);
		if (null !== $group) $this->buildGraph($group);
	}

	/**
	 * Строит двунаправленный граф для указанной группы
	 * @param Groups $group
	 * @throws Throwable
	 */
	public function buildGraph(Groups $group):void {
		$processedStack[$group->id] = true;
		$this->nodes[] = new GroupNode($group);
		$this->buildGraphUp($group, $processedStack);
		$this->buildGraphDown($group, $processedStack);
	}

	/**
	 * Строит граф вниз от указанной группы
	 * @param Groups $group
	 * @param array $processedStack -- массив обработанных групп для предотвращения зацикливания
	 * @param int $currentDepth
	 * @throws Throwable
	 */
	public function buildGraphDown(Groups $group, array &$processedStack = [], int &$currentDepth = 0):void {
		if ($this->downDepth < 0 || $currentDepth < $this->downDepth) {
			$currentDepth++;
			$processedStack[$group->id] = true;
			/** @var Groups $childGroup */
			foreach ((array)$group->relChildGroups as $childGroup) {
				if (false === ArrayHelper::getValue($processedStack, $childGroup->id, false)) {
					$this->nodes[] = new GroupNode($childGroup, ['y' => $currentDepth]);//позиционирование по y может использоваться при серверном расчёте координат, но его можно игнорировать при клиентском расчёте
					$processedStack[$childGroup->id] = true;
					$this->buildGraphDown($childGroup, $processedStack, $currentDepth);
				}

				$edge = new GroupEdge($group, $childGroup);
				if (!in_array($edge->id, ArrayHelper::getColumn($this->edges, "id"), true)) {
					$this->edges[] = $edge;
				}

			}
			$currentDepth--;
		}
	}

	/**
	 * Строит граф вверх от указанной группы
	 * @param Groups $group
	 * @param array $processedStack -- массив обработанных групп для предотвращения зацикливания
	 * @param int $currentDepth
	 * @throws Throwable
	 */
	public function buildGraphUp(Groups $group, array &$processedStack = [], int &$currentDepth = 0):void {
		if ($this->upDepth < 0 || $currentDepth < $this->upDepth) {
			$processedStack[$group->id] = true;
			$currentDepth++;
			/** @var Groups $parentGroup */
			foreach ((array)$group->relParentGroups as $parentGroup) {
				if (false === ArrayHelper::getValue($processedStack, $parentGroup->id, false)) {
					$this->nodes[] = new GroupNode($parentGroup, ['y' => 1 * $currentDepth]);
					$processedStack[$parentGroup->id] = true;
					$this->buildGraphUp($parentGroup, $processedStack, $currentDepth);

				}
				$edge = new GroupEdge($parentGroup, $group);
				if (!in_array($edge->id, ArrayHelper::getColumn($this->edges, "id"), true)) {
					$this->edges[] = $edge;
				}
			}
			$currentDepth--;
		}
	}

	/**
	 * @param mixed $upDepth
	 */
	public function setUpDepth(mixed $upDepth):void {
		$this->upDepth = $upDepth;
	}

	/**
	 * @param mixed $downDepth
	 */
	public function setDownDepth(mixed $downDepth):void {
		$this->downDepth = $downDepth;
	}

	/**
	 * Применяет набор позиций к текущим нодам
	 * @param array $positions -- позиции в формате [nodeId => [x,y]]
	 */
	public function applyNodesPositions(array $positions = []):void {
		foreach ($positions as $nodeId => $position) {
			if (false !== ($key = array_search($nodeId, array_column($this->nodes, 'id'), true))) {
				/** @var int $key */
				$this->nodes[$key]->x = $position['x'];
				$this->nodes[$key]->y = $position['y'];
			}

		}
	}


}