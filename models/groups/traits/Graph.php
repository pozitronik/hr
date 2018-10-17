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
		return [
			'id' => (string)$this->id,
			'label' => "$x,$y",//$this->name,
			'x' => $x,
			'y' => $y,
			'size' => (string)3,//count($this->relUsers),//todo: придумать характеристику веса группы,
			'color' => "rgb({$red},{$green},{$blue})"
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
			'label' => $to->relGroupTypes->name,
			'size' => '30'
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
//		Utils::fileLog("$x,$y");
		/** @var Groups $this */
		$childStack[$this->id] = true;
		$graphStack[] = $this->asNode($x, $y);

		/** @var Groups $childGroup */
		$y++;
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
//		Utils::fileLog("$x,$y");
		/** @var Groups $this */
		if (!isset($graphMap[$level + 1])) $graphMap[$level + 1] = 0;
		$graphMap[$level + 1] = $graphMap[$level + 1] + count($this->relChildGroups);

		/** @var Groups $childGroup */

		foreach ($this->relChildGroups as $childGroup) {
			$level++;
			$childGroup->getGraphMap($graphMap, $level);
			$level--;
		}

	}
}