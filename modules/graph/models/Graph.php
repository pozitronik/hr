<?php
declare(strict_types = 1);

namespace app\modules\graph\models;

use yii\base\ArrayableTrait;
use yii\base\Model;

/**
 * Class Graph -- базовый класс графа
 * @package app\modules\graph\models
 *
 * @property int $upDepth -- глубина построения дерева вверх. 0 - только текущий уровень, отрицательное значение - нет ограничения
 * @property int $downDepth -- глубина построения дерева вниз. 0 - только текущий уровень, отрицательное значение - нет ограничения
 * @property GraphNode[] $nodes
 * @property GraphEdge[] $edges
 */
class Graph extends Model implements GraphInterface {

	use ArrayableTrait;

	private $upDepth = -1;
	private $downDepth = -1;
	public $nodes = [];
	public $edges = [];

	/**
	 *
	 */
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
	 * @return Graph
	 */
	public static function combine(array $graphs):Graph {
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

	/**
	 *
	 */
	private function setUnique():void {
		$nodeIds = [];
		$edgeIds = [];
		$this->nodes = array_filter($this->nodes, static function(GraphNode $node) use (&$nodeIds) {
			if (in_array($node->id, $nodeIds, true)) {
				return false;
			}
			$nodeIds[] = $node->id;
			return true;
		});

		$this->nodes = array_values($this->nodes);//reindexing required, cause vis.js vil fail otherwise

		$this->edges = array_filter($this->edges, static function(GraphEdge $edge) use (&$edgeIds) {
			if (in_array($edge->id, $edgeIds, true)) {
				return false;
			}
			$edgeIds[] = $edge->id;
			return true;
		});

		$this->edges = array_values($this->edges);

	}

}