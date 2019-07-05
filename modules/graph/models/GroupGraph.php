<?php
declare(strict_types = 1);

namespace app\modules\graph\models;

use app\modules\groups\models\Groups;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\ArrayableTrait;
use yii\base\Model;

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
class GroupGraph extends Model {//todo GraphInterface
	use ArrayableTrait;

	private $upDepth = -1;
	private $downDepth = -1;
	public $nodes = [];
	public $edges = [];

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
				if (!in_array($edge->id, ArrayHelper::getColumn($this->edges, "id"))) {
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

	public function roundNodes():void {
		$levelMap = [];
		foreach ($this->nodes as $node) {//распределение нод по уровням круга.
			$levelMap[$node->y][] = $node;
		}
		/** @var GraphNode[] $items */
		foreach ($levelMap as $level => $items) {
			$c_items = count($items) / 2;//Я не знаю, зачем делить на два, я не академик
			$degree = 360 / $c_items;//Угловое смещение точки

			$radius = (0 === $level)?$level:($level + 1);
			$radius *= 360;
			$angle = 0;//Стартовый угол, 0 - 360
			/** @var GraphNode[] $items */
			foreach ($items as $item) {//Почему-то координаты применяются к текущим нодам, хотя работаем мы с копией, а не ссылкой. Нам это ок, но как-то странно
				$item->x = ($radius * cos($angle * M_PI / 360));
				$item->y = ($radius * sin($angle * M_PI / 360));
				$angle += $degree;
			}
		}
	}

	/**
	 * Объединяет несколько переданных графов в одну карту
	 * @param self[] $graphs
	 * @return GroupGraph
	 */
	public static function combine(array $graphs):GroupGraph {
		$result = new self();
		$resultNodes = [[]];
		$resultEdges = [[]];
		foreach ($graphs as $graph) {
			$resultNodes[] = $graph->nodes;
			$resultEdges[] = $graph->edges;
		}
		$result->nodes = array_merge(...$resultNodes);
		$result->edges = array_merge(...$resultEdges);
		$result->setUnique();
		return $result;
	}

	private function setUnique():void {
		$nodeIds = [];
		$edgeIds = [];
		$this->nodes = array_filter($this->nodes, static function(GraphNode $node) use (&$nodeIds) {
			if (in_array($node->id, $nodeIds)) {
				return false;
			}
			$nodeIds[] = $node->id;
			return true;
		});

		$this->nodes = array_values($this->nodes);//reindexing required, cause vis.js vil fail otherwise

		$this->edges = array_filter($this->edges, static function(GraphEdge $edge) use (&$edgeIds) {
			if (in_array($edge->id, $edgeIds)) {
				return false;
			}
			$edgeIds[] = $edge->id;
			return true;
		});

		$this->edges = array_values($this->edges);

	}

}