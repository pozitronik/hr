<?php
declare(strict_types = 1);

namespace app\models\groups\traits;

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use Exception;
use Throwable;

/**
 * Экспериментальный трейт для групп, все функции построения графа структуры
 */
trait Graph {

	private static function circle(&$x, &$y, int $r = 1) {
		$alpha = 10;
		$x = round($x + $r * cos($alpha * pi() / 360));
		$y = round($y * $r * sin($alpha * pi() / 360));

//		for ($alpha = 1; $alpha <= 360; $alpha++) {
//
//		}
	}

	/**
	 * @param null|integer $x
	 * @param null|integer $y
	 * @return array
	 * @throws Exception
	 */
	public function asNode($x = 0, $y = 0):array {
		/** @var Groups $this */
		$red = random_int(10, 255);
		$green = random_int(10, 255);
		$blue = random_int(10, 255);
//		$size = (count($this->relUsers) + count($this->relChildGroups));
		$size = 100 / ($y + 1);
		/*Экспериментируем: в нашем случае Y - уровень отстояния от точки 0,0*/
//		if (1 === $y) {
//			self::circle($x, $y, 10);
//		}

		return [
			'id' => (string)$this->id,
			'label' => "$x,$y",
			'x' => $x,
			'y' => $y,
			'size' => (string)$size,//todo: придумать характеристику веса группы,
			'color' => "rgb({$red},{$green},{$blue})",
			'type' => 'circle',
			'image' => [
				'url' => $this->leader->avatar,
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
		return [
			'id' => "{$this->id}x{$to->id}",
			'source' => (string)$this->id,
			'target' => (string)$to->id,
			'type' => 'curvedArrow',
			'label' => $to->leader->username,
			'size' => '5'
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
	 * @return array
	 */
	public function roundGraph(array $nodes):array {
		$levelMap = [];
		$newNodes = [];
		foreach ($nodes as $node) {
			$levelMap[$node['y']][] = $node;
		}
		foreach ($levelMap as $level => $items) {
			$c_items = count($items)/2;
			$radius = $c_items*($level+1);
			$i = 0;
			foreach ($items as $item) {
				$item['x'] = ($radius * cos($i * pi() / 360));
				$item['y'] = ($radius * sin($i * pi() / 360));
				$i += 360/$c_items;
				$newNodes[] = $item;
			}
		}
		return $newNodes;
	}
}