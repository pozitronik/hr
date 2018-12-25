<?php
declare(strict_types = 1);

namespace app\models\groups\traits;

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use app\models\relations\RelGroupsGroups;
use Exception;
use Throwable;

/**
 * Экспериментальный трейт для групп, все функции построения графа структуры
 */
trait Graph {

	/**
	 * @param null|integer $x
	 * @param null|integer $y
	 * @return array
	 * @throws Exception
	 * @throws Throwable
	 */
	public function asNode($x = 0, $y = 0):array {
		/** @var Groups $this */
		$red = random_int(10, 255);
		$green = random_int(10, 255);
		$blue = random_int(10, 255);
//		$size = (count($this->relUsers) + count($this->relChildGroups));
		$size = 360 / ($y + 1);
//		$size = $y;
		return [
			'id' => (string)$this->id,
			'label' => $this->name,
			'x' => $x,
			'y' => $y,
			'size' => (string)$size,//todo: придумать характеристику веса группы,
			'color' => ArrayHelper::getValue($this->relGroupTypes, 'color', "rgb({$red},{$green},{$blue})"),
			'type' => 'circle',
			'image' => [
				'url' => $this->logo,
				'clip' => '0.95',
				'scale' => '1.4'
			]
		];
	}

	/**
	 * @param self $to
	 * @return array
	 */
	private function Edge($to):array {
//		if (false === $color = RelGroupsGroups::getRelationColor($this->id, $to->id)) {
//			 //todo: цвет вычисляется, как средний между цветом исходящей группы и входящей группы
//		}
		return [
			'id' => "{$this->id}x{$to->id}",
			'source' => (string)$this->id,
			'target' => (string)$to->id,
			'type' => 'curvedArrow',
			'label' => $to->leader->username,
			'size' => '5',
			'color' => RelGroupsGroups::getRelationColor($this->id, $to->id)
		];
	}

	/**
	 * @param array $graphStack
	 * @param array $edgesStack
	 * @param array $childStack
	 * @param int $y
	 * @param int $x
	 * @throws Throwable
	 */
	public function getGraph(&$graphStack = [], &$edgesStack = [], array &$childStack = [], &$x = 0, &$y = 0):void {
		/** @var Groups $this */
		$childStack[$this->id] = true;
		$graphStack[] = $this->asNode($x, $y);

		/** @var Groups $childGroup */
		$y++;
		/** @noinspection ForeachSourceInspection */
		foreach ($this->relChildGroups as $childGroup) {
			$edgesStack[] = $this->Edge($childGroup);

			if (false === ArrayHelper::getValue($childStack, $childGroup->id, false)) {
				$childStack[$childGroup->id] = true;
				$childGroup->getGraph($graphStack, $edgesStack, $childStack, $x, $y);
			}
		}
		$x++;
		$y--;
	}

	/**
	 * Строим матрицу распределения узлов графа структуры
	 * @param array $graphMap
	 * @param int $level
	 */
	public function getGraphMap(array &$graphMap = [0 => 0], &$level = 0):void {
		/** @var Groups $this */
		if (!isset($graphMap[$level + 1])) $graphMap[$level + 1] = 0;
		$graphMap[$level + 1] += count($this->relChildGroups);

		/** @var Groups $childGroup */

		/** @noinspection ForeachSourceInspection */
		foreach ($this->relChildGroups as $childGroup) {
			$level++;
			$childGroup->getGraphMap($graphMap, $level);
			$level--;
		}
	}

	/**
	 * Пересчитываем координаты графа в круговые
	 * @param array $nodes
	 */
	public function roundGraph(array &$nodes):void {
		$levelMap = [];
		$newNodes = [];
		foreach ($nodes as $node) {
			$levelMap[$node['y']][] = $node;
		}
		foreach ($levelMap as $level => $items) {
			$c_items = count($items) / 2;//Я не знаю, зачем делить на два, я не академик
			$degree = 360 / $c_items;//Угловое смещение точки

			$radius = (0 === $level)?$level:($level + 1);
			$radius *= 360;
			$angle = 0;//Стартовый угол, 0 - 360
			/** @var array $items */
			foreach ($items as $item) {
				$item['x'] = ($radius * cos($angle * M_PI / 360));
				$item['y'] = ($radius * sin($angle * M_PI / 360));

				$angle += $degree;
				$newNodes[] = $item;
			}
		}
		$nodes = $newNodes;
	}

	/**
	 * Заменяет высчитанные позиции нод заданными
	 * @param array $nodes
	 * @param array $positions
	 */
	public function applyNodesPositions(array &$nodes, array $positions = []):void {
		foreach ($positions as $nodeId => $position) {
			if (false !== ($key = array_search($nodeId, array_column($nodes, 'id')))) {
				/** @var integer $key */
				$nodes[$key]['x'] = $position['x'];
				$nodes[$key]['y'] = $position['y'];
			}

		}
	}
}